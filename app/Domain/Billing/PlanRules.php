<?php

namespace App\Domain\Billing;

use App\Models\User;
use RuntimeException;

final class PlanRules
{
    public const PREMIUM_MONTHLY   = 'premium_monthly';
    public const PREMIUM_YEARLY    = 'premium_yearly';
    public const PREMIUM_LIFETIME  = 'premium_lifetime';
    public const BB_ONETIME        = 'bb_onetime'; // represents Beta Breaker Lifetime

    public const BB_TO_PREMIUM_LIFETIME_DELTA_CENTS = 10000; // $100

    public static function isPremiumLifetime(User $u): bool
    {
        return $u->plan === self::PREMIUM_LIFETIME;
    }

    public static function isBBLifetime(User $u): bool
    {
        return $u->plan === self::BB_ONETIME;
    }

    public static function isOnPremiumRecurring(User $u): bool
    {
        return in_array($u->plan, [self::PREMIUM_MONTHLY, self::PREMIUM_YEARLY, 'active_recurring'], true);
    }

    /** Starting recurring sub (monthly/yearly) */
    public static function assertCanStartRecurring($u, string $targetPlan): void
    {
        if (self::isPremiumLifetime($u)) {
            throw new RuntimeException('Premium Lifetime users cannot change or downgrade.');
        }

        if (self::isBBLifetime($u)) {
            throw new RuntimeException('Beta Breaker Lifetime users cannot subscribe to monthly/yearly or freemium.');
        }

        if (!in_array($targetPlan, [self::PREMIUM_MONTHLY, self::PREMIUM_YEARLY], true)) {
            throw new RuntimeException('Invalid recurring plan requested.');
        }
    }

    /** Buying BB Lifetime (bb_onetime) */
    public static function assertCanBuyBBLifetime($u): void
    {
        if (self::isPremiumLifetime($u)) {
            throw new RuntimeException('Premium Lifetime users do not need Beta Breaker Lifetime.');
        }

        if (self::isBBLifetime($u)) {
            throw new RuntimeException('You already own Beta Breaker Lifetime.');
        }

        // Freemium and Premium recurring users can buy it at full price.
    }

    /** Buying Premium Lifetime */
    public static function lifetimeChargeCents($u, int $fullLifetimePriceCents): int
    {
        if (self::isPremiumLifetime($u)) {
            throw new RuntimeException('User already has Premium Lifetime.');
        }

        if (self::isBBLifetime($u)) {
            return self::BB_TO_PREMIUM_LIFETIME_DELTA_CENTS; // $100 top-up
        }

        return $fullLifetimePriceCents; // full payment for others
    }

    /** Swap: Monthly ↔ Yearly only */
    public static function assertSwapAllowed($u, string $to): void
    {
        if (self::isPremiumLifetime($u) || self::isBBLifetime($u)) {
            throw new RuntimeException('This account cannot change plan.');
        }

        $current = $u->plan;
        $ok = (
            ($current === self::PREMIUM_MONTHLY && $to === self::PREMIUM_YEARLY) ||
            ($current === self::PREMIUM_YEARLY && $to === self::PREMIUM_MONTHLY)
        );

        if (!$ok) {
            throw new RuntimeException('This plan change is not allowed.');
        }
    }
}
