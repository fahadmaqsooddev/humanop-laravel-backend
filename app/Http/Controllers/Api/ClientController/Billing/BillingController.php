<?php

namespace App\Http\Controllers\Api\ClientController\Billing;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Client\Plan\Plan;
use Illuminate\Http\Request;
use App\Support\StripeConfig;
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
                'family'  => 'b2c'
            ],
        ]);

        return response()->json([
            'setup_intent_id' => $setupIntent->id,
            'client_secret'   => $setupIntent->client_secret,
            'publishable_key' => StripeConfig::publishableKey(),
        ]);
    }

    public function finalizePaymentMethod(Request $request)
    {
        $validated = $request->validate([
            'setup_intent_id' => 'required|string',
        ]);

        $user = Helpers::getUser();

        $user->createOrGetStripeCustomer();

        $si = $this->stripe->setupIntents->retrieve($validated['setup_intent_id'], []);

        if ($si->status !== 'succeeded') {
            return response()->json(['status' => false, 'message' => 'SetupIntent is not succeeded. Please try again.',], 422);
        }

        $pmId = $si->payment_method;

        // Idempotent: attach then set default

        $this->stripe->paymentMethods->attach($pmId, ['customer' => $user->stripe_id]);

        // Set as default for future invoices/subscriptions

        $this->stripe->customers->update($user->stripe_id, [
            'invoice_settings' => ['default_payment_method' => $pmId],
        ]);

        $user->syncDefaultPmFromStripe($this->stripe, $pmId);

        return response()->json(['status' => true]);
    }

    public function getDefaultPaymentMethod(Request $request)
    {
        $user = Helpers::getUser();

        $user->createOrGetStripeCustomer();

        $cust = $this->stripe->customers->retrieve($user->stripe_id, [

            'expand' => ['invoice_settings.default_payment_method'],
        ]);

        $pm = $cust->invoice_settings->default_payment_method;

        if (!$pm) {
            return response()->json(['has_pm' => false]);
        }

        $masked = [
            'id'       => $pm->id,
            'type'     => $pm->type,
            'brand'    => $pm->card->brand ?? null,
            'last4'    => $pm->card->last4 ?? null,
            'exp_month'=> $pm->card->exp_month ?? null,
            'exp_year' => $pm->card->exp_year ?? null,
        ];

        return response()->json(['has_pm' => true, 'payment_method' => $masked]);
    }

    public function initSubscription(Request $request)
    {
        $validated = $request->validate([
            'plan'  => 'required|string', // 'premium_monthly' etc.
            'name'  => 'nullable|string',
            'email' => 'nullable|email',
            'payment_method' => 'nullable|string',
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

        // Resolve default PM (or accept explicit one)
        $pmId = $request->string('payment_method')->toString() ?: null;

        if (!$pmId) {

            $cust = $this->stripe->customers->retrieve($user->stripe_id, [
                'expand' => ['invoice_settings.default_payment_method'],
            ]);

            $pmId = $cust->invoice_settings->default_payment_method->id ?? null;

        }
        if (!$pmId) {
            return response()->json(['status'  => false, 'message' => 'No default payment method. Please add a card first.', 'code'    => 'card_required',], 422);
        }

        // Create incomplete sub with explicit default PM
        $subscription = $this->stripe->subscriptions->create([
            'customer' => $user->stripe_id,
            'items'    => [['price' => $priceId]],
            'default_payment_method' => $pmId,
            'payment_behavior'       => 'default_incomplete',
            'payment_settings'       => ['save_default_payment_method' => 'on_subscription'],
            'proration_behavior'     => 'none',
            'metadata' => ['user_id' => (string)$user->getKey(), 'family' => 'b2c'],
        ]);

        // optimistic local flags
        $user->plan            = $validated['plan']; // e.g., premium_monthly
        $user->is_lifetime     = false;
        $user->billing_context = 'b2c';
        $user->save();

        $latestInvoiceId = $subscription->latest_invoice ?? null;
        if (!$latestInvoiceId) {
            return response()->json([
                'subscription_id' => $subscription->id,
                'status'          => $subscription->status,
                'requires_action' => false,
                'message'         => 'No invoice generated.',
            ], 200);
        }

        // Force “first charge now” in your account:
        try {

            $this->stripe->invoices->finalizeInvoice($latestInvoiceId, []);

        } catch (\Throwable $e) {}

        $paidInvoice = $this->stripe->invoices->pay($latestInvoiceId, ['off_session' => true]);

        // Re-fetch with PI/charge expanded
        $invoice = $this->stripe->invoices->retrieve($latestInvoiceId, [
            'expand' => ['payment_intent', 'charge'],
        ]);

        // If paid, we’re done — no client_secret needed
        if ($invoice->status === 'paid' || $paidInvoice->status === 'paid') {
            // (optional) persist masked PM again to be safe
            $user->syncDefaultPmFromStripe($this->stripe, $pmId);

            return response()->json([
                'subscription_id' => $subscription->id,
                'status'          => 'active',
                'requires_action' => false,
                'client_secret'   => null,
                'publishable_key' => StripeConfig::publishableKey(),
                'invoice_id'      => $invoice->id,
                'payment_intent_id' => $invoice->payment_intent->id ?? ($invoice->charge ? $this->stripe->charges->retrieve($invoice->charge)->payment_intent : null),
            ]);
        }

        // If SCA required, return PI client_secret so frontend can confirm
        $pi = $invoice->payment_intent ?? null;
        if ($pi && in_array($pi->status, ['requires_action','requires_confirmation','requires_payment_method'])) {
            return response()->json([
                'subscription_id' => $subscription->id,
                'status'          => $subscription->status, // likely "incomplete"
                'requires_action' => true,
                'client_secret'   => $pi->client_secret,
                'publishable_key' => StripeConfig::publishableKey(),
                'invoice_id'      => $invoice->id,
            ]);
        }

        // Fallback
        return response()->json([
            'subscription_id' => $subscription->id,
            'status'          => $subscription->status,
            'requires_action' => false,
            'client_secret'   => null,
            'publishable_key' => StripeConfig::publishableKey(),
            'invoice_id'      => $invoice->id,
            'invoice_status'  => $invoice->status,
        ]);

    }

    public function initLifetime(Request $request)
    {
        $validated = $request->validate([
            'plan'  => 'required|string', // e.g. 'premium_lifetime'
            'name'  => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        // Validate plan & resolve Stripe price id
        StripeConfig::ensureOneTime($validated['plan'], 'b2c');
        $priceId = StripeConfig::priceId($validated['plan'], 'b2c');

        $user = Helpers::getUser();
        $user->createOrGetStripeCustomer();

        // Keep Stripe customer profile fresh
        $this->stripe->customers->update($user->stripe_id, array_filter([
            'name'  => $validated['name']  ?? null,
            'email' => $validated['email'] ?? $user->email,
        ]));

        // Get price
        $price = $this->stripe->prices->retrieve($priceId, ['expand' => ['product']]);
        if ($price->type !== 'one_time') {
            abort(422, 'Selected plan is not a one-time price.');
        }

        // Resolve default PM on the customer
        $cust = $this->stripe->customers->retrieve($user->stripe_id, [
            'expand' => ['invoice_settings.default_payment_method'],
        ]);
        $defaultPmId = $cust->invoice_settings->default_payment_method->id ?? null;

        // If we have a saved card, first try to charge it off-session (no UI)
        if ($defaultPmId) {
            try {
                $pi = $this->stripe->paymentIntents->create([
                    'amount'         => $price->unit_amount,
                    'currency'       => $price->currency,
                    'customer'       => $user->stripe_id,
                    'payment_method' => $defaultPmId,
                    'confirm'        => true,     // attempt immediately
                    'off_session'    => true,     // no UI; bank may still require SCA
                    'description'    => $price->product->name ?? 'Lifetime Access',
                    'metadata'       => [
                        'user_id'      => (string) $user->getKey(),
                        'family'       => 'b2c',
                        'purpose'      => 'lifetime',
                        'plan'         => $validated['plan'], // 'premium_lifetime'
                        'product_name' => $price->product->name ?? '',
                        'price_id'     => $priceId,
                    ],
                ]);

                // Succeeded without SCA → no client_secret needed
                if ($pi->status === 'succeeded') {
                    return response()->json([
                        'status'             => 'succeeded',
                        'requires_action'    => false,
                        'payment_intent_id'  => $pi->id,
                    ]);
                }

                // Needs SCA → return client_secret so frontend can confirm on-session
                if (in_array($pi->status, ['requires_action', 'requires_confirmation', 'requires_payment_method'], true)) {
                    return response()->json([
                        'status'           => 'requires_action',
                        'requires_action'  => true,
                        'payment_intent_id'=> $pi->id,
                        'client_secret'    => $pi->client_secret,
                        'publishable_key'  => StripeConfig::publishableKey(),
                    ]);
                }
            } catch (\Stripe\Exception\CardException $e) {
                // Typical SCA edge: authentication_required → return PI secret
                $err = $e->getError();
                $pi  = $err->payment_intent ?? null;
                if (($err->code ?? null) === 'authentication_required' || ($pi?->status ?? null) === 'requires_action') {
                    return response()->json([
                        'status'           => 'requires_action',
                        'requires_action'  => true,
                        'payment_intent_id'=> $pi?->id,
                        'client_secret'    => $pi?->client_secret,
                        'publishable_key'  => StripeConfig::publishableKey(),
                    ]);
                }

                // Hard decline or other card error
                return response()->json([
                    'status'  => 'failed',
                    'message' => $e->getMessage(),
                    'code'    => $err->code ?? null,
                ], 402);
            }
        }

        // No saved card (or you want AME fallback): return a PI client_secret for Payment Element
        $pi = $this->stripe->paymentIntents->create([
            'amount'                     => $price->unit_amount,
            'currency'                   => $price->currency,
            'customer'                   => $user->stripe_id,
            'automatic_payment_methods'  => ['enabled' => true],
            'description'                => $price->product->name ?? 'Lifetime Access',
            'metadata'                   => [
                'user_id'      => (string) $user->getKey(),
                'family'       => 'b2c',
                'purpose'      => 'lifetime',
                'plan'         => $validated['plan'],
                'product_name' => $price->product->name ?? '',
                'price_id'     => $priceId,
            ],
        ]);

        return response()->json([
            'status'             => 'requires_payment_method',  // frontend should render Payment Element
            'requires_action'    => true,
            'payment_intent_id'  => $pi->id,
            'client_secret'      => $pi->client_secret,
            'publishable_key'    => StripeConfig::publishableKey(),
        ]);
    }

    public function initBBOneTime(Request $request)
    {
        $validated = $request->validate([
            'plan'  => 'required|string', // e.g. 'bb_onetime'
            'name'  => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        // Validate plan & resolve Stripe price id
        StripeConfig::ensureOneTime($validated['plan'], 'b2c');
        $priceId = StripeConfig::priceId($validated['plan'], 'b2c');

        $user = Helpers::getUser();
        $user->createOrGetStripeCustomer();

        // Keep Stripe customer profile fresh
        $this->stripe->customers->update($user->stripe_id, array_filter([
            'name'  => $validated['name']  ?? null,
            'email' => $validated['email'] ?? $user->email,
        ]));

        // Get price (for amount/currency/product name)
        $price = $this->stripe->prices->retrieve($priceId, ['expand' => ['product']]);
        if ($price->type !== 'one_time') {
            abort(422, 'Selected plan is not a one-time price.');
        }

        // Resolve default PM on the customer
        $cust = $this->stripe->customers->retrieve($user->stripe_id, [
            'expand' => ['invoice_settings.default_payment_method'],
        ]);
        $defaultPmId = $cust->invoice_settings->default_payment_method->id ?? null;

        // If we have a saved card, first try to charge it off-session (no UI)
        if ($defaultPmId) {
            try {
                $pi = $this->stripe->paymentIntents->create([
                    'amount'         => $price->unit_amount,
                    'currency'       => $price->currency,
                    'customer'       => $user->stripe_id,
                    'payment_method' => $defaultPmId,
                    'confirm'        => true,     // attempt immediately
                    'off_session'    => true,     // no UI; bank may still require SCA
                    'description'    => $price->product->name ?? 'BB Onetime',
                    'metadata'       => [
                        'user_id'      => (string) $user->getKey(),
                        'family'       => 'b2c',
                        'purpose'      => 'bb_onetime',
                        'plan'         => $validated['plan'], // 'bb_onetime'
                        'product_name' => $price->product->name ?? '',
                        'price_id'     => $priceId,
                    ],
                ]);

                // Succeeded without SCA → no client_secret needed
                if ($pi->status === 'succeeded') {
                    return response()->json([
                        'status'             => 'succeeded',
                        'requires_action'    => false,
                        'payment_intent_id'  => $pi->id,
                    ]);
                }

                // Needs SCA → return client_secret so frontend can confirm on-session
                if (in_array($pi->status, ['requires_action', 'requires_confirmation', 'requires_payment_method'], true)) {
                    return response()->json([
                        'status'             => 'requires_action',
                        'requires_action'    => true,
                        'payment_intent_id'  => $pi->id,
                        'client_secret'      => $pi->client_secret,
                        'publishable_key'    => StripeConfig::publishableKey(),
                    ]);
                }
            } catch (\Stripe\Exception\CardException $e) {
                // Typical SCA edge: authentication_required → return PI secret
                $err = $e->getError();
                $pi  = $err->payment_intent ?? null;

                if (($err->code ?? null) === 'authentication_required' || ($pi?->status ?? null) === 'requires_action') {
                    return response()->json([
                        'status'             => 'requires_action',
                        'requires_action'    => true,
                        'payment_intent_id'  => $pi?->id,
                        'client_secret'      => $pi?->client_secret,
                        'publishable_key'    => StripeConfig::publishableKey(),
                    ]);
                }

                // Hard decline or other card error
                return response()->json([
                    'status'  => 'failed',
                    'message' => $e->getMessage(),
                    'code'    => $err->code ?? null,
                ], 402);
            }
        }

        // AME fallback: no saved card → return PI client_secret for Payment Element
        $pi = $this->stripe->paymentIntents->create([
            'amount'                    => $price->unit_amount,
            'currency'                  => $price->currency,
            'customer'                  => $user->stripe_id,
            'automatic_payment_methods' => ['enabled' => true],
            'description'               => $price->product->name ?? 'BB Onetime',
            'metadata'                  => [
                'user_id'      => (string) $user->getKey(),
                'family'       => 'b2c',
                'purpose'      => 'bb_onetime',
                'plan'         => $validated['plan'],
                'product_name' => $price->product->name ?? '',
                'price_id'     => $priceId,
            ],
        ]);

        return response()->json([
            'status'             => 'requires_payment_method',  // frontend should render Payment Element
            'requires_action'    => true,
            'payment_intent_id'  => $pi->id,
            'client_secret'      => $pi->client_secret,
            'publishable_key'    => StripeConfig::publishableKey(),
        ]);
    }


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
            $user->plan = 'freemium';
        }
        $user->save();

        return response()->json([
            'ok' => true,
            'subscription' => $sub->refresh(),
        ]);
    }

    public function resume(Request $request)
    {
        $user = $request->user();
        $sub = $user->subscription('default');

        if (!$sub || !$sub->onGracePeriod()) {
            abort(422, 'Not in grace period.');
        }

        $sub->resume();

        if (!$user->is_lifetime) {
            if ($user->plan === 'freemium' || !$user->plan) {
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


    public static function plans()
    {

        try {

            $getPlans = Plan::getB2CPlans();

            $plans = [];

            foreach ($getPlans as $plan) {
                $plans[] = [
                    'id' => $plan->id,
                    'plan_id' => $plan->plan_id,
                    'name' => $plan->name,
                    'billing_method' => $plan->billing_method,
                    'price' => Helpers::getUser()['plan_key'] == "premium_lifetime"  && $plan->key == "premium_lifetime" ? 100 : $plan->price,
                    'currency' => $plan->currency,
                    'status' => $plan->status,
                    'key' => $plan->key,
                    'kind' => $plan->kind,
                    'product_name' => $plan->product_name,
                    'active' => $plan->active,
                    'context' => $plan->context,
                    'plan_details' => $plan->plan_details,
                ];
            }

            return Helpers::successResponse('All plans', $plans);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

}
