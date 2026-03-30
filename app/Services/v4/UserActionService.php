<?php 

namespace App\Services\v4;

use App\Events\UserActionPerformed;
use App\Enums\UserActions\UserActions;
use Illuminate\Support\Facades\DB;

class UserActionService
{
    public static function dispatch(int $userId, UserActions $action, array $details = []): void
    {
       $event = new UserActionPerformed($userId, $action->value, $details);

        if (DB::transactionLevel() > 0) {

            DB::afterCommit(fn() => event($event));
            
        } else {

            event($event);
        }
    }
}