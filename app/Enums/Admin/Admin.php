<?php


namespace App\Enums\Admin;


final class Admin
{
    const IS_ADMIN = 1;

    const IS_CUSTOMER = 2;

    const SUB_ADMIN = 3;

    const IS_PRACTITIONER = 4;

    const IS_B2B = 5;

    const IS_B2U = 6;

    const IS_MALE = 0;

    const IS_FEMALE = 1;

    const IS_BOTH = 2;

    const HAI_CHAT_HIDE = 2;

    const NINETY_DAYS_ACTION_PLAN = 2;

    const FOURTEEN_DAYS_ACTION_PLAN = 1;

    const HAI_CHAT_SHOW = 1;

    const SUPER_ADMIN_ROLE = 1;

    const SUB_ADMIN_ROLE = 2;

    const PRACTITIONER_ROLE = 3;

    const TWO_WAY_AUTH_ACTIVE = 1;

    const TWO_WAY_AUTH_DISABLED = 2;

    const INTRO_CHECK_READ = 1;

    const INTRO_CHECK_UN_READ = 2;

    const RESET_ASSESSMENT = 1;

    const NOT_RESET_ASSESSMENT = 0;

    const Is_Feed_Back_Show = 1;

    const DAILY_TIP_INFO = 4;

    const CORE_STATS_INFO = 5;

    const ACTION_PLAN_INFO = 6;

    const RESOURCE_INFO = 7;

    const CHALLENGE_BUTTON_INFO = 8;

    const HAI_CHAT_INFO = 9;

    const PROFILE_OVERVIEW_INFO = 10;

    const INTEGRATION_PODCAST_INFO = 11;

    const DAILY_TIP_NOTIFICATION = 1;

    const RESET_ASSESSMENT_NOTIFICATION = 2;

    const TRAINING_RESOURCE_NOTIFICATION = 3;

    const ANNOUNCEMENT_NEWS_NOTIFICATION = 14;

    const REMOVE_COMPANY_NOTIFICATION = 15;

    const NETWORK_NOTIFICTAION = 4;
    const MESSAGE_SEND_NOTIFICATION = 9;

    const NEW_MESSAGE_NOTIFICATION = 10;

    const B2B_SHARE_DATA_NOTIFICATION = 11;

    const B2B_NOT_SHARE_DATA_NOTIFICATION = 12;

    const REQUEST_ACCESS_DATA_NOTIFICATION = 13;

    const OPTIMAL_TRAIT = 14;

    const CREDIT_BONUS = 15;

    const CLIENT_INVITE_ROLE = 1;

    const B2B_INVITE_ROLE = 2;

    const B2B_MEMBER_INVITE_ROLE = 3;

    const MORNING_STATUS = 1;

    const AFTERNOON_STATUS = 2;

    const NIGHT_STATUS = 3;

    const LARGEST_TRAIT = 1;

    const SECOND_TRAIT = 2;

    const THIRD_TRAIT = 3;

    const PILOT_TRAIT = 4;

    const CO_PILOT_TRAIT = 5;

    const ALCHEMY_TRAIT = 6;

    const COMMUNICATION_TRAIT = 7;

    const POLARITY_TRAIT = 8;

    const ENERGY_POOL_TRAIT = 9;

    const IN_FUTURE = 1;

    const NOT_IN_FUTURE = 0;

    const IS_CANDIDATE = 1;

    const IS_TEAM_MEMBER = 0;

    const SHARED_DATA = 1;

    const NOT_SHARED_DATA = 0;

    const DECLINED_DATA = 3;

    const FUTURE_CONSIDERATION_SHARE_DATA = 1;

    const FUTURE_CONSIDERATION_NOT_SHARE_DATA = 0;

    const B2B_NOTIFICATION = 1;

    const B2C_NOTIFICATION = 0;

    const B2B_PLAN = 1;

    const B2C_PLAN = 0;

    const REQUEST_SEND = 1;

    const ISSUE_FIXED = 0;

    const NEW_FEATURE = 1;

    const B2B_INACTIVE_PLAN = 0;

    const B2B_ACTIVE_PLAN = 1;

    const FREEMIUM_CREDITS = 25;

    const CORE_CREDITS = 150;

    const PREMIUM_CREDITS = 250;

    const SHARE_ASSESSMENT = 1;

    const NOT_SHARE_ASSESSMENT = 2;

    const FAVORITE_DAILY_TIP = 2;

    const NOT_FAVORITE_DAILY_TIP = 1;

    const DAILY_LOGIN_POINT_FOR_CLARITY = 5;

    const COMPLETE_ASSESSMENT_POINT_FOR_CLARITY = 250;

    const COMPLETE_WATCH_VIDEO_POINT_FOR_CLARITY = 30;

    const COMPLETE_ALL_WATCH_VIDEOS_POINT_FOR_CLARITY = 50;

    const COMPLETE_DAILY_TIP_POINT_FOR_CLARITY = 10;

    const COMPLETE_VIDEO = 'Completed';

    const NOT_COMPLETE_VIDEO = 'Not Completed';

    const ASSESSMENT_BADGES = 'Assessment Navigator';

    const WATCH_VIDEO_BADGES = 'Video Voyager';

    const WATCH_VIDEO_MEDAL = 'Welcome Optimizer';

    const FIRST_LEVEL = 'Explorer';

    const SECOND_LEVEL = 'Seeker';

    const TRIAL_DAY = 14;

    const VERIFIED_EMAIL ='verified_email';

    CONST RESET_PASSWORD= 'reset_password';

    const FA_VERIFICATION_CODE = '2fa_verification_code';

    const B2B_SIGNUP_LINK='b2b_signup_link';

    const B2B_LOGIN_LINK = 'b2b_login_link';

    const MAESTRO_SIGNUP = 'maestro_signup';

    const B2B_EMAIL_VERIFICATION='b2b_email_verification';


}
