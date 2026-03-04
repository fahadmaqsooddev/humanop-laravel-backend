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
           
            // Log::info('SendPushNotification started', [
            //     'notification_id' => $notification->id,
            //     'user_id' => $notification->user_id,
            //     'send_push_flag' => $event->sendPush,
            // ]);

            // OneSignal
            if ($event->sendPush && $notification->user_id) {
                try {
                    NotificationService::sendOneSignal($notification);
                    Log::info('OneSignal notification sent', [
                        'notification_id' => $notification->id,
                        'user_id' => $notification->user_id,
                    ]);
                } catch (\Exception $osEx) {
                    Log::error('OneSignal push failed', [
                        'notification_id' => $notification->id,
                        'user_id' => $notification->user_id,
                        'exception_message' => $osEx->getMessage(),
                        'exception_trace' => $osEx->getTraceAsString(),
                    ]);
                }
            }

            // FCM
            if (!empty($notification->device_token)) {
                try {
                    NotificationService::sendFCM($notification);
                    Log::info('FCM notification sent', [
                        'notification_id' => $notification->id,
                        'user_id' => $notification->user_id,
                    ]);
                } catch (\Exception $fcmEx) {
                    Log::error('FCM push failed', [
                        'notification_id' => $notification->id,
                        'user_id' => $notification->user_id,
                        'exception_message' => $fcmEx->getMessage(),
                        'exception_trace' => $fcmEx->getTraceAsString(),
                    ]);
                }
            }

        } catch (\Exception $e) {
        
            Log::error('Error in SendPushNotification', [
                'notification_id' => $notification->id,
                'user_id' => $notification->user_id,
                'exception_message' => $e->getMessage(),
                'exception_trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}