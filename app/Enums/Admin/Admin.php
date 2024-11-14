<?php


namespace App\Enums\Admin;


final class Admin
{
    const IS_ADMIN = 1;

    const IS_CUSTOMER = 2;

    const IS_MALE = 0;

    const IS_FEMALE = 1;

    const IS_BOTH = 2;

    const SUB_ADMIN = 3;

    const IS_PRACTITIONER = 4;

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

}
