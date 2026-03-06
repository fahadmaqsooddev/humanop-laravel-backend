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
}