<?php

namespace App\Support;

use App\Models\Admin\StripeSetting\StripeSetting;
use App\Models\Client\Plan\Plan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class StripeConfig
{
    const CACHE_SETTINGS = 'stripe_settings.active';
    const CACHE_PLAN_PREFIX = 'plan.slug.'; // we'll append context

    // ------- Keys (publishable, secret, webhook) -------

    public static function publishableKey(): string
    {
        $row = self::settings();
        if (empty($row?->public_key)) {
            throw new RuntimeException('Stripe publishable key missing.');
        }
        return $row->public_key;
    }

    public static function secretKey(): string
    {
        $row = self::settings();
        if (empty($row?->api_key)) {
            throw new RuntimeException('Stripe secret key missing.');
        }
        return $row->api_key;
    }

    public static function webhookSecret(): string
    {
        $row = self::settings();
        if (empty($row?->webhook_secret)) {
            throw new RuntimeException('Stripe webhook secret missing.');
        }
        return $row->webhook_secret;
    }

    protected static function settings(): ?StripeSetting
    {
        return Cache::remember(
            self::CACHE_SETTINGS,
            now()->addMinutes(5),
            fn() => StripeSetting::query()->where('type', 1)->first()
        );
    }

    public static function refreshSettingsCache(): void
    {
        Cache::forget(self::CACHE_SETTINGS);
        self::settings();
    }

    // ------- Plans / Prices (context aware for future B2B) -------

    public static function planBySlug(string $slug, string $context = 'b2c'): ?Plan
    {
        $cacheKey = self::CACHE_PLAN_PREFIX . $context . '.' . $slug;

        return Cache::remember(
            $cacheKey,
            now()->addMinutes(10),
            fn() => Plan::active()
                ->forContext($context)
                ->where('key', $slug)
                ->first()
        );
    }

    public static function refreshPlanCache(string $slug, string $context = 'b2c'): void
    {
        Cache::forget(self::CACHE_PLAN_PREFIX . $context . '.' . $slug);
        self::planBySlug($slug, $context);
    }

    public static function priceId(string $slug, string $context = 'b2c'): string
    {
        $plan = self::planBySlug($slug, $context);
        if (!$plan || !$plan->plan_id) {
            throw new RuntimeException("Plan [$slug] in [$context] is not active / missing price_id.");
        }
        return $plan->plan_id;
    }

    public static function ensureOneTime(string $slug, string $context = 'b2c'): void
    {
        $plan = self::planBySlug($slug, $context);
        Log::info(print_r($plan, true));
        if (!$plan || $plan->kind !== 'one_time') {
            throw new RuntimeException("Plan [$slug] in [$context] is not one_time.");
        }
    }

    public static function ensureRecurring(string $slug, string $context = 'b2c'): void
    {
        $plan = self::planBySlug($slug, $context);
        if (!$plan || $plan->kind !== 'recurring') {
            throw new RuntimeException("Plan [$slug] in [$context] is not recurring.");
        }
    }

}
