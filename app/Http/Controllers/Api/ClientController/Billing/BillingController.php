<?php

namespace App\Http\Controllers\Api\ClientController\Billing;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Support\StripeConfig;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Subscription;
use Stripe\StripeClient;

class BillingController extends Controller
{

    public function __construct(private StripeClient $stripe)
    {
    }

    public function initPaymentMethod(Request $request)
    {
        $user = Helpers::getUser();

        // make sure user has stripe customer
        $user->createOrGetStripeCustomer();

        // create SetupIntent for collecting a reusable payment method
        $setupIntent = $this->stripe->setupIntents->create([
            'customer' => $user->stripe_id,
            'automatic_payment_methods' => ['enabled' => true],
            'usage' => 'off_session', // we want to be able to charge later automatically
            'metadata' => [
                'user_id' => (string) $user->getKey(),
                'family'  => 'b2c',
                'purpose' => 'collect_default_payment_method',
            ],
        ]);

        return response()->json([
            'setup_intent_id' => $setupIntent->id,
            'client_secret'   => $setupIntent->client_secret,
            'publishable_key' => StripeConfig::publishableKey(),
        ]);
    }

    public function initSubscription(Request $request)
    {
        $validated = $request->validate([
            'plan'  => 'required|string', // 'premium_monthly' etc.
            'name'  => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        StripeConfig::ensureRecurring($validated['plan'], 'b2c');

        $priceId = StripeConfig::priceId($validated['plan'], 'b2c');

        $user = Helpers::getUser();

        // Ensure Stripe customer exists
        $user->createOrGetStripeCustomer();

        // Sync basic info
        $this->stripe->customers->update($user->stripe_id, array_filter([
            'name'  => $validated['name']  ?? null,
            'email' => $validated['email'] ?? $user->email,
        ]));

        // OPTIONAL BUT SMART:
        // Fetch default_payment_method from the customer in case we need to send it explicitly
        $stripeCustomer = $this->stripe->customers->retrieve($user->stripe_id, [
            'expand' => ['invoice_settings.default_payment_method'],
        ]);

        $defaultPmId = $stripeCustomer->invoice_settings->default_payment_method->id ?? null;

        // Create subscription in incomplete state
        $subscriptionParams = [
            'customer' => $user->stripe_id,
            'items'    => [
                ['price' => $priceId],
            ],
            'payment_behavior' => 'default_incomplete',
            'payment_settings' => [
                'save_default_payment_method' => 'on_subscription',
            ],
            'proration_behavior' => 'none',
            'metadata' => [
                'user_id' => (string) $user->getKey(),
                'family'  => 'b2c',
            ],
        ];

        // If we know the default PM, tell Stripe explicitly so there's no ambiguity
        if ($defaultPmId) {
            $subscriptionParams['default_payment_method'] = $defaultPmId;
        }

        $subscription = $this->stripe->subscriptions->create($subscriptionParams);

        // Sync optimistic local state
        $user->plan            = $validated['plan'];
        $user->is_lifetime     = false;
        $user->billing_context = 'b2c';
        $user->save();

        $latestInvoiceId = $subscription->latest_invoice ?? null;

        if (!$latestInvoiceId) {
            return response()->json([
                'subscription_id' => $subscription->id,
                'status'          => $subscription->status,
                'requires_action' => false,
                'message'         => 'No invoice generated for this subscription.',
            ], 200);
        }

        // 1. Finalize the invoice (if not already finalized).
        // Stripe will throw if already finalized, so we wrap in try.
        try {
            $this->stripe->invoices->finalizeInvoice($latestInvoiceId, []);
        } catch (\Throwable $e) {
            // ignore if already finalized
        }

        // 2. Attempt to pay the invoice using the customer's default payment method.
        // This is the step your account is NOT doing automatically.
        $paidInvoice = $this->stripe->invoices->pay($latestInvoiceId, [
            // Force automatic payment with the default PM.
            // We don't pass payment_method here because default is already set.
            'off_session' => true,
        ]);

        // 3. Get the invoice again, with payment_intent expanded,
        //    so we can see if Stripe needs user action (3DS).
        $invoice = $this->stripe->invoices->retrieve($latestInvoiceId, [
            'expand' => ['payment_intent'],
        ]);

        Log::info("Invoice:\n" . json_encode($invoice, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $pi = $invoice->payment_intent ?? null;

        // Case A: Invoice is fully paid, no action needed.
        // paidInvoice.status could be "paid"
        if ($invoice->status === 'paid' || $paidInvoice->status === 'paid') {
            return response()->json([
                'subscription_id' => $subscription->id,
                'status'          => 'active', // effectively active now
                'requires_action' => false,
                'client_secret'   => null,
                'publishable_key' => StripeConfig::publishableKey(),
            ], 200);
        }

        // Case B: Stripe needs SCA / 3DS challenge.
        if ($pi && !empty($pi->client_secret)) {
            // PI will typically be in requires_action / requires_confirmation
            return response()->json([
                'subscription_id' => $subscription->id,
                'status'          => $subscription->status, // likely "incomplete"
                'requires_action' => true,
                'client_secret'   => $pi->client_secret,
                'publishable_key' => StripeConfig::publishableKey(),
            ], 200);
        }

        // Case C: Still no PI after forcing pay. Edge case.
        return response()->json([
            'subscription_id' => $subscription->id,
            'status'          => $subscription->status,
            'requires_action' => false,
            'client_secret'   => null,
            'invoice_status'  => $invoice->status,
            'message'         => 'Invoice created but no PaymentIntent attached even after pay(); may already be covered by saved mandate or payment method type.',
            'publishable_key' => StripeConfig::publishableKey(),
        ], 200);
    }


    /**
     * One-time payment for premium_lifetime.
     * Returns client_secret for Payment Element.
     */
    public function initLifetime(Request $request)
    {
        $validated = $request->validate([
            'plan' => 'required|string', // 'premium_lifetime'
            'name' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        StripeConfig::ensureOneTime($validated['plan'], 'b2c');
        $priceId = StripeConfig::priceId($validated['plan'], 'b2c');

        /** @var \App\Models\User $user */
        $user = $request->user();

        $user->createOrGetStripeCustomer();

        $this->stripe->customers->update($user->stripe_id, array_filter([
            'name' => $validated['name'] ?? null,
            'email' => $validated['email'] ?? $user->email,
        ]));

        // Look up the Stripe Price to know amount/currency
        $price = $this->stripe->prices->retrieve($priceId, [
            'expand' => ['product'],
        ]);

        if ($price->type !== 'one_time') {
            abort(422, 'Selected plan is not a one-time price.');
        }

        $pi = $this->stripe->paymentIntents->create([
            'amount' => $price->unit_amount,
            'currency' => $price->currency,
            'customer' => $user->stripe_id,
            'automatic_payment_methods' => ['enabled' => true],
            'description' => $price->product->name ?? 'Lifetime Access',

            'metadata' => [
                'user_id' => (string)$user->getKey(),
                'family' => 'b2c',
                'purpose' => 'lifetime',
                'plan' => $validated['plan'],   // 'premium_lifetime'
                'product_name' => $price->product->name ?? '',
                'price_id' => $priceId,
            ],
        ]);

        return response()->json([
            'payment_intent_id' => $pi->id,
            'client_secret' => $pi->client_secret,
            'publishable_key' => StripeConfig::publishableKey(),
        ]);
    }

    /**
     * One-time payment for BB-onetime / add-on.
     */
    public function initBBOneTime(Request $request)
    {
        $validated = $request->validate([
            'plan' => 'required|string', // 'bb_onetime'
            'name' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        StripeConfig::ensureOneTime($validated['plan'], 'b2c');
        $priceId = StripeConfig::priceId($validated['plan'], 'b2c');

        /** @var \App\Models\User $user */
        $user = $request->user();

        $user->createOrGetStripeCustomer();

        $this->stripe->customers->update($user->stripe_id, array_filter([
            'name' => $validated['name'] ?? null,
            'email' => $validated['email'] ?? $user->email,
        ]));

        $price = $this->stripe->prices->retrieve($priceId, [
            'expand' => ['product'],
        ]);

        if ($price->type !== 'one_time') {
            abort(422, 'Selected plan is not a one-time price.');
        }

        $pi = $this->stripe->paymentIntents->create([
            'amount' => $price->unit_amount,
            'currency' => $price->currency,
            'customer' => $user->stripe_id,
            'automatic_payment_methods' => ['enabled' => true],
            'description' => $price->product->name ?? 'BB Onetime',

            'metadata' => [
                'user_id' => (string)$user->getKey(),
                'family' => 'b2c',
                'purpose' => 'bb_onetime',
                'plan' => $validated['plan'], // 'bb_onetime'
                'product_name' => $price->product->name ?? '',
                'price_id' => $priceId,
            ],
        ]);

        return response()->json([
            'payment_intent_id' => $pi->id,
            'client_secret' => $pi->client_secret,
            'publishable_key' => StripeConfig::publishableKey(),
        ]);
    }

    /**
     * Swap between recurring plans (premium_monthly <-> premium_yearly).
     * "Option A": features now, same renewal date, no proration charge right now.
     *
     * NOTE:
     * For an upgrade from freemium into paid,
     * you usually do initSubscription() + Payment Element instead
     * because Stripe will need a card. After first successful charge,
     * swapPlan() becomes the normal move between paid tiers.
     */
    public function swapPlan(Request $request)
    {
        $validated = $request->validate([
            'to' => 'required|string', // 'premium_monthly' | 'premium_yearly'
        ]);

        StripeConfig::ensureRecurring($validated['to'], 'b2c');
        $toPriceId = StripeConfig::priceId($validated['to'], 'b2c');

        $user = $request->user();
        $sub = $user->subscription('default');

        if (!$sub || !$sub->valid()) {
            abort(422, 'No active subscription to swap.');
        }

        $sub->swap($toPriceId, [
            'proration_behavior' => 'none',
            'billing_cycle_anchor' => 'unchanged',
        ]);

        $user->plan = $validated['to'];
        $user->is_lifetime = false;
        $user->billing_context = 'b2c';
        $user->save();

        return response()->json(['ok' => true]);
    }

    /**
     * Cancel a paid subscription at period end (grace).
     * We do NOT touch lifetime users.
     */
    public function cancelAtPeriodEnd(Request $request)
    {
        $user = $request->user();
        $sub = $user->subscription('default');

        if (!$sub || !$sub->active()) {
            abort(422, 'No active subscription.');
        }

        $sub->cancel(); // cancel_at_period_end on Stripe side

        // locally, you may choose to instantly downgrade UI:
        if (!$user->is_lifetime) {
            $user->plan = 'free';
        }
        $user->save();

        return response()->json([
            'ok' => true,
            'subscription' => $sub->refresh(),
        ]);
    }

    /**
     * Resume subscription during grace period.
     */
    public function resume(Request $request)
    {
        $user = $request->user();
        $sub = $user->subscription('default');

        if (!$sub || !$sub->onGracePeriod()) {
            abort(422, 'Not in grace period.');
        }

        $sub->resume();

        if (!$user->is_lifetime) {
            if ($user->plan === 'free' || !$user->plan) {
                $user->plan = 'resumed';
            }
            $user->billing_context = 'b2c';
        }

        $user->save();

        return response()->json([
            'ok' => true,
            'subscription' => $sub->refresh(),
        ]);
    }

    /**
     * Optional status polling for frontend.
     */
    public function status(Request $request, string $stripeSubscriptionId)
    {
        $sub = Subscription::where('stripe_id', $stripeSubscriptionId)->first();
        if (!$sub) {
            abort(404, 'Subscription not found.');
        }

        return response()->json([
            'status' => $sub->stripe_status,
            'on_grace_period' => $sub->onGracePeriod(),
            'ends_at' => optional($sub->ends_at)?->toIso8601String(),
        ]);
    }
}
