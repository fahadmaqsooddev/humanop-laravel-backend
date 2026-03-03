<?php

namespace App\Listeners;

use App\Events\NotificationCreated;
use App\Services\v4\NotificationService\NotificationService;
use Illuminate\Support\Facades\Log;


class SendNotificationPush
{
    /**
     * Handle the event.
     */
    public function handle(NotificationCreated $event): void
    {
        $notification = $event->notification;


        //  Log::info('SendNotificationPush listener triggered.', [
        //     'notification_id' => $notification->id ?? null,
        //     'user_id' => $notification->user_id ?? null,
        //     'type' => $notification->type ?? null,
        //     'message' => $notification->message ?? null,
        // ]);

    if (!empty($notification->device_token)) {
            // Log::info('Sending FCM notification.', [
            //     'device_token' => $notification->device_token,
            //     'title' => $notification->type,
            //     'body' => $notification->message,
            // ]);
            NotificationService::sendFCM($notification);
        }

        // Send OneSignal push if flagged
       if ($event->sendPush && $notification->user_id) {
            // Log::info('Sending OneSignal notification.', [
            //     'user_id' => $notification->user_id,
            //     'title' => $notification->type,
            //     'body' => $notification->message,
            // ]);
            NotificationService::sendOneSignal($notification);
        }

        // Log::info('SendNotificationPush listener completed.', [
        //     'notification_id' => $notification->id ?? null,
        // ]);
    }
}