<?php 

namespace App\Services\v4;

use App\Events\UserActionPerformed;
use App\Enums\UserActions\UserActions;

class UserActionService
{
    public static function dispatch(int $userId, UserActions $action, array $details = []): void
    {
        event(new UserActionPerformed($userId, $action->value, $details));
    }
}