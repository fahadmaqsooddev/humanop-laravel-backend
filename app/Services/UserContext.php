<?php

namespace App\Services;

use App\Models\User;
use App\Helpers\Helpers;


class UserContext
{
    protected static ?UserContext $instance = null;
    protected ?User $user = null;

    private function __construct()
    {
        $this->user = Helpers::getUser();
    }

    public static function getInstance(): UserContext
    {
        if (self::$instance === null) {
            self::$instance = new UserContext();
        }
        return self::$instance;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getUserId(): int
    {
        return $this->user->id;
    }

    public function getUserGender(): int
    {
        return (int) $this->user->getRawOriginal('gender');
    }
}

