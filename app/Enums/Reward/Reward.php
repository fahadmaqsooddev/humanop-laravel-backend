<?php

namespace App\Enums\Reward;

enum Reward: string
{
    case DAILY_LOGIN = 'daily_login';
    case DAILY_LOGIN_BONUS = 'daily_login_bonus';
    case DAILY_LOGIN_RESET = 'daily_login_reset';
    case ASSESSMENT_COMPLETED = 'assessment_completed';
    case VIDEO_WATCHED = 'video_watched';
    case ALL_VIDEOS_COMPLETED = 'all_videos_completed';
    case DAILY_TIP_COMPLETED = 'daily_tip_completed';
    case SUBSCRIPTION_PURCHASE = 'subscription_purchase';
    case LIFETIME_PURCHASE = 'lifetime_purchase';
    case PLAN_SWAP = 'plan_swap';
    case COUPON_REDEMPTION = 'coupon_redemption';
    case APP_SUBSCRIPTION_SYNC = 'app_subscription_sync';

    public function label(): string
    {
        return match($this) {
            self::DAILY_LOGIN => 'Daily Login',
            self::DAILY_LOGIN_BONUS => 'Daily Login Bonus',
            self::DAILY_LOGIN_RESET => 'Daily Login Reset',
            self::ASSESSMENT_COMPLETED => 'Assessment Completed',
            self::VIDEO_WATCHED => 'Video Watched',
            self::ALL_VIDEOS_COMPLETED => 'All Videos Completed',
            self::DAILY_TIP_COMPLETED => 'Daily Tip Completed',
            self::SUBSCRIPTION_PURCHASE => 'Subscription Purchase',
            self::LIFETIME_PURCHASE => 'Lifetime Purchase',
            self::PLAN_SWAP => 'Plan Swap',
            self::COUPON_REDEMPTION => 'Coupon Redemption',
            self::APP_SUBSCRIPTION_SYNC => 'App Subscription Sync',
        };
    }
}