<?php 

namespace App\Services\v4;

use App\Events\UserActionPerformed;
use App\Enums\UserActions\UserActions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserActionService
{
    public static function dispatch(int $userId, UserActions $action, array $details = []): void
    {
        $safeDetails = self::sanitize($action, $details);

        $event = new UserActionPerformed($userId, $action->value, $safeDetails);

        if (DB::transactionLevel() > 0) {
            DB::afterCommit(fn() => event($event));
        } else {
            event($event);
        }
    }

    private static function sanitize(UserActions $action, array $details): array
    {
        return match ($action) {

           
            UserActions::MESSAGE_SENT => [
                'receiver_id' => $details['receiver_id'] ?? null,
                'thread_id'   => $details['thread_id'] ?? null,
                'message_preview' => isset($details['message'])
                    ? Str::limit($details['message'], 50)
                    : ($details['message_preview'] ?? null),
            ],

          
            UserActions::NEW_DAILY_TIP => [
                'message_preview' => isset($details['message'])
                    ? Str::limit($details['message'], 50)
                    : null,
            ],

            UserActions::CONNECTION_REQUEST_SENT,
            UserActions::CONNECTION_REMOVED,
            UserActions::CONNECTION_ACCEPTED => [
                'receiver_id' => $details['receiver_id'] ?? null,
                'sender_id'   => $details['sender_id'] ?? null,
            ],

           
            UserActions::FOLLOWED,
            UserActions::UNFOLLOWED => [
                'follower_id' => $details['follower_id'] ?? null,
            ],

            
            UserActions::GROUP_REQUEST_SENT,
            UserActions::ACCEPT_GROUP_REQUEST,
            UserActions::REJECT_GROUP_REQUEST => [
                'thread_id' => $details['thread_id'] ?? null,
                'member_id' => $details['member_id'] ?? null,
                'group_name' => isset($details['group_name'])
                    ? Str::limit($details['group_name'], 50)
                    : null,
            ],

          
            UserActions::REMOVE_COMPANY => [
                'company_id' => $details['company_id'] ?? null,
            ],

            default => [],
        };
    }
}