<?php

namespace App\Http\Controllers\Api\ClientController\Billing;

use App\Http\Controllers\Controller;
use App\Support\StripeConfig;
use Illuminate\Http\Request;
use Laravel\Cashier\Subscription;
use Stripe\StripeClient;

class B2CController extends Controller
{

    public function __construct(private StripeClient $stripe)
    {
    }

    /** Recurring subscription (Payment Element) */
    public function initSubscription(Request $request)
    {
        $validated = $request->validate([
            'plan' => 'required|string', // 'premium_monthly' | 'premium_yearly'
            'name' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        StripeConfig::ensureRecurring($validated['plan']);
        $price = StripeConfig::priceId($validated['plan']);

        /** @var \App\Models\User $user */
        $user = $request->user();
        $user->createOrGetStripeCustomer();

        // sync customer (optional)
        $this->stripe->customers->update($user->stripe_id, array_filter([
            'name' => $validated['name'] ?? null,
            'email' => $validated['email'] ?? $user->email,
        ]));

        // Create incomplete sub; confirm PaymentIntent on frontend
        $subscription = $this->stripe->subscriptions->create([
            'customer' => $user->stripe_id,
            'items' => [['price' => $price]],
            'payment_behavior' => 'default_incomplete',
            'payment_settings' => ['save_default_payment_method' => 'on_subscription'],
            'billing_cycle_anchor' => 'now',
            'proration_behavior' => 'none',
            'expand' => ['latest_invoice.payment_intent', 'items.data.price.product'],
            'metadata' => [
                'user_id' => (string)$user->getKey(),
                'family' => 'b2c',
            ],
        ]);

        // Optimistic local label (webhook will confirm)
        $user->plan = 'premium';
        $user->is_lifetime = false;
        $user->save();

        $pi = $subscription->latest_invoice->payment_intent;

        return response()->json([
            'subscription_id' => $subscription->id,
            'client_secret' => $pi->client_secret,
            'publishable_key' => StripeConfig::publishableKey(),
        ]);
    }

    /** Lifetime (one-time, no subscription) */
    public function initLifetime(Request $request)
    {
        $validated = $request->validate([
            'plan' => 'required|string', // 'premium_lifetime'
            'name' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        StripeConfig::ensureOneTime($validated['plan']);
        $priceId = StripeConfig::priceId($validated['plan']);

        /** @var \App\Models\User $user */
        $user = $request->user();
        $user->createOrGetStripeCustomer();

        $this->stripe->customers->update($user->stripe_id, array_filter([
            'name' => $validated['name'] ?? null,
            'email' => $validated['email'] ?? $user->email,
        ]));

        $price = $this->stripe->prices->retrieve($priceId, ['expand' => ['product']]);
        if ($price->type !== 'one_time') abort(422, 'Selected plan is not a one-time price.');

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
                'plan' => $validated['plan'], // keep exact lifetime slug
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

    /** BB-Onetime (one-time, non-lifetime) */
    public function initBBOneTime(Request $request)
    {
        $validated = $request->validate([
            'plan' => 'required|string', // 'bb_onetime'
            'name' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        StripeConfig::ensureOneTime($validated['plan']);
        $priceId = StripeConfig::priceId($validated['plan']);

        /** @var \App\Models\User $user */
        $user = $request->user();
        $user->createOrGetStripeCustomer();

        $this->stripe->customers->update($user->stripe_id, array_filter([
            'name' => $validated['name'] ?? null,
            'email' => $validated['email'] ?? $user->email,
        ]));

        $price = $this->stripe->prices->retrieve($priceId, ['expand' => ['product']]);
        if ($price->type !== 'one_time') abort(422, 'Selected plan is not a one-time price.');

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
                'plan' => $validated['plan'],
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

    /** Option-A swap (features now, no proration, keep renewal) */
    public function swapPlan(Request $request)
    {
        $validated = $request->validate(['to' => 'required|string']); // 'premium_monthly'|'premium_yearly'
        StripeConfig::ensureRecurring($validated['to']);
        $toPrice = StripeConfig::priceId($validated['to']);

        $user = $request->user();
        $sub = $user->subscription('default');
        if (!$sub || !$sub->valid()) abort(422, 'No active subscription to swap.');

        $sub->swap($toPrice, [
            'proration_behavior' => 'none',
            'billing_cycle_anchor' => 'unchanged',
        ]);

        $user->plan = 'premium';
        $user->is_lifetime = false;
        $user->save();

        return response()->json(['ok' => true]);
    }

    /** Cancel at period end */
    public function cancelAtPeriodEnd(Request $request)
    {
        $user = $request->user();
        $sub = $user->subscription('default');
        if (!$sub || !$sub->active()) abort(422, 'No active subscription.');

        $sub->cancel(); // grace
        $user->plan = 'free';
        $user->is_lifetime = false;
        $user->save();

        return response()->json(['ok' => true, 'subscription' => $sub->refresh()]);
    }

    /** Resume during grace */
    public function resume(Request $request)
    {
        $user = $request->user();
        $sub = $user->subscription('default');
        if (!$sub || !$sub->onGracePeriod()) abort(422, 'Not in grace period.');

        $sub->resume();
        $user->plan = 'premium';
        $user->is_lifetime = false;
        $user->save();

        return response()->json(['ok' => true, 'subscription' => $sub->refresh()]);
    }

    /** OPTIONAL: status endpoint for quick UI polling */
    public function status(Request $request, string $stripeSubscriptionId)
    {
        $sub = Subscription::where('stripe_id', $stripeSubscriptionId)->first();
        if (!$sub) abort(404, 'Subscription not found.');
        return response()->json([
            'status' => $sub->stripe_status,
            'on_grace_period' => $sub->onGracePeriod(),
            'ends_at' => optional($sub->ends_at)?->toIso8601String(),
        ]);
    }
}
