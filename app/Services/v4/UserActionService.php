<?php 

namespace App\Services\v4;

use App\Events\UserActionPerformed;
use App\Enums\UserActions\UserActions;
use Illuminate\Support\Facades\DB;

class UserActionService
{
    public static function dispatch(int $userId, UserActions $action, array $details = []): void
    {
        DB::afterCommit(function () use ($userId, $action, $details) {
            event(new UserActionPerformed(
                $userId,
                $action->value,
                $details
            ));
        });
    }
}