<?php

namespace App\Services;

use App\Support\StripeConfig;
use App\Models\User;
use Stripe\StripeClient;


class FreemiumEnrollmentService
{
    public function __construct(private StripeClient $stripe)
    {
    }

    /**
     * Ensure the user has a Stripe $0 "Freemium" subscription and a local 'default' subscription row.
     * Safe to call multiple times.
     */
    public function enroll(User $user): void
    {
        // If they already have an active subscription (premium etc.), do not override.
        $existing = $user->subscription('default');
        if ($existing && $existing->valid()) {
            return;
        }

        // Make sure they're a Stripe customer
        $user->createOrGetStripeCustomer();

        // Get Freemium price from plans table
        $freemiumPriceId = StripeConfig::priceId('Freemium', 'b2c');

        // Create $0 subscription in Stripe
        $stripeSub = $this->stripe->subscriptions->create([
            'customer' => $user->stripe_id,
            'items' => [
                ['price' => $freemiumPriceId],
            ],
            'proration_behavior' => 'none',
            'billing_cycle_anchor' => 'now',
            'metadata' => [
                'user_id' => (string)$user->getKey(),
                'purpose' => 'Freemium',
                'family' => 'b2c', // <- future proof tag
            ],
        ]);

        // Mirror into local cashier subscriptions table
        $user->subscriptions()->updateOrCreate(
            ['name' => 'default'],
            [
                'stripe_id' => $stripeSub->id,
                'stripe_status' => $stripeSub->status,       // 'active'
                'stripe_price' => $freemiumPriceId,
                'quantity' => 1,
                'trial_ends_at' => null,
                'ends_at' => null,
            ]
        );

        // Mark app-level access flags
        $user->plan = 'Freemium';
        $user->is_lifetime = false;
        $user->has_bb_onetime = $user->has_bb_onetime ?? false;
        $user->billing_context = 'b2c';
        $user->save();
    }
}
