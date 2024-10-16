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


    private static $intentionOption = [
             1 => 'Personal Growth and Development',
             2 => 'Business Optimization',
             3 => 'Relationship Optimization',
             4 => 'Career Optimization',
             5 => 'Team Optimization',
             6 => 'Health & Fitness',
    ];

    public static function getIntentionOption($value)
    {
        return self::$intentionOption[$value] ?? '';
    }
}
