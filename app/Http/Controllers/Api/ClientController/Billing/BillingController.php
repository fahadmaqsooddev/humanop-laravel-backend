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

    /**
     * Start a recurring subscription (premium_monthly / premium_yearly).
     * Returns client_secret for Payment Element.
     */
    public function initSubscription(Request $request)
    {
        $validated = $request->validate([
            'plan' => 'required|string', // e.g. 'premium_monthly'
            'name' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        // Ensure it's a recurring (paid) plan in b2c
        StripeConfig::ensureRecurring($validated['plan'], 'b2c');
        $priceId = StripeConfig::priceId($validated['plan'], 'b2c');

        $user = Helpers::getUser();

        Log::info("User:\n" . json_encode($user, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        // make sure user is a Stripe customer
        $user->createOrGetStripeCustomer();

        // Keep their Stripe profile tidy (for invoices)
        $this->stripe->customers->update($user->stripe_id, array_filter([
            'name' => $validated['name'] ?? null,
            'email' => $validated['email'] ?? $user->email,
        ]));

        // Create an "incomplete" subscription draft so Stripe
        // gives us a PaymentIntent that we can confirm client-side.
        $subscription = $this->stripe->subscriptions->create([
            'customer' => $user->stripe_id,
            'items' => [
                ['price' => $priceId],
            ],
            'payment_behavior' => 'default_incomplete',
            'payment_settings' => [
                'save_default_payment_method' => 'on_subscription',
            ],
            'proration_behavior' => 'none',
            'expand' => [
                'latest_invoice.payment_intent',
                'items.data.price.product',
            ],
            'metadata' => [
                'user_id' => (string)$user->getKey(),
                'family' => 'b2c', // ready for b2b later
            ],
        ]);

        // optimistic marking; webhook will finalize truth
        $user->plan = $validated['plan']; // like 'premium_monthly'
        $user->is_lifetime = false;
        $user->billing_context = 'b2c';
        $user->save();

        $pi = $subscription->latest_invoice->payment_intent;

        return response()->json([
            'subscription_id' => $subscription->id,
            'client_secret' => $pi->client_secret,
            'publishable_key' => StripeConfig::publishableKey(),
        ]);
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
