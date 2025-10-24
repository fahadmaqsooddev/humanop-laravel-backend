<?php

namespace App\Support;

use App\Enums\Admin\Admin;
use App\Models\Admin\StripeSetting\StripeSetting;
use App\Models\Plan;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

class StripeConfig
{
    const CACHE_SETTINGS = 'stripe_settings.active';
    const CACHE_PLAN_PREFIX = 'plan.slug.';

    public static function publishableKey(): string
    {
        $row = self::settings();

        if (empty($row?->public_key)) {

            throw new RuntimeException('Stripe publishable key is not configured.');

        }

        return $row->public_key;

    }

    public static function secretKey(): string
    {

        $row = self::settings();

        if (empty($row?->api_key)) {

            throw new RuntimeException('Stripe secret key is not configured.');

        }

        return $row->api_key;

    }

    public static function webhookSecret(): string
    {

        $row = self::settings();

        if (empty($row?->webhook_secret)) {

            throw new RuntimeException('Stripe webhook secret is not configured.');

        }

        return $row->webhook_secret;

    }

    protected static function settings(): ?StripeSetting
    {

        return Cache::remember(self::CACHE_SETTINGS, now()->addMinutes(5), function () {

            return StripeSetting::query()->where('type', Admin::B2B_ACTIVE_PLAN)->first();

        });

    }

    public static function refreshSettingsCache(): void
    {

        Cache::forget(self::CACHE_SETTINGS);

        self::settings();

    }

    public static function priceId(string $slug): string
    {

        $plan = self::planBySlug($slug);

        if (!$plan || empty($plan->plan_id)) {

            throw new RuntimeException("Unknown or inactive plan [$slug].");

        }

        return $plan->plan_id;

    }

    public static function ensureOneTime(string $slug): void
    {

        $plan = self::planBySlug($slug);

        if (!$plan || $plan->kind !== 'one_time') {

            throw new RuntimeException("Plan [$slug] is not a one-time plan.");

        }

    }

    public static function ensureRecurring(string $slug): void
    {

        $plan = self::planBySlug($slug);

        if (!$plan || $plan->kind !== 'recurring') {

            throw new RuntimeException("Plan [$slug] is not a recurring plan.");

        }

    }

    public static function planBySlug(string $slug): ?Plan
    {

        return Cache::remember(self::CACHE_PLAN_PREFIX . $slug, now()->addMinutes(10), function () use ($slug) {

            return Plan::active()->where('key', $slug)->first();

        });

    }

    public static function refreshPlanCache(string $slug): void
    {

        Cache::forget(self::CACHE_PLAN_PREFIX . $slug);

        self::planBySlug($slug);

    }

}
