<?php

namespace App\Listeners;

use App\Events\NotificationCreated;
use App\Services\v4\NotificationService\NotificationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPushNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */

    public $tries = 3;
    public $timeout = 120;
    
    public function handle(NotificationCreated $event): void
    {
        $notification = $event->notification;

         try {
            // FCM Notification bhejne ki koshish
            if (!empty($notification->device_token)) {
                NotificationService::sendFCM($notification);
            }

            // OneSignal notification bhejne ki koshish, agar flag true ho aur user_id ho
            if ($event->sendPush && $notification->user_id) {
                NotificationService::sendOneSignal($notification);
            }

        } catch (\Exception $e) {
            // Agar koi error aaye, toh usse log karenge
            Log::error('Error sending push notification: ' . $e->getMessage(), [
                'notification_id' => $notification->id ?? null,
                'user_id' => $notification->user_id ?? null,
                'type' => $notification->type ?? null,
                'message' => $notification->message ?? null,
                'device_token' => $notification->device_token ?? null,
                'send_push_flag' => $event->sendPush,
                'error_message' => $e->getMessage(),
            ]);
            
            // Exception ko rethrow karte hain, taake retry mechanism kaam kare
            throw $e;
        }
    }
}