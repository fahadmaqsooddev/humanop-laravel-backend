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

    const CONNECTION_REQUEST_NOTIFICATION = 4;

    const CONNECTION_ACCEPT_NOTIFICATION = 5;

    const CONNECTION_CANCEL_NOTIFICATION = 6;

    const FOLLOW_REQUEST_NOTIFICATION = 7;

    const UN_FOLLOW_REQUEST_NOTIFICATION = 8;

    const MESSAGE_SEND_NOTIFICATION = 9;

    const NEW_MESSAGE_NOTIFICATION = 10;

    const CLIENT_INVITE_ROLE = 1;

    const B2B_INVITE_ROLE = 2;

    const MORNING_STATUS = 1;

    const AFTERNOON_STATUS = 2;

    const NIGHT_STATUS = 3;
}
