<?php

namespace App\Listeners;

use App\Events\NotificationCreated;
use App\Services\v4\NotificationService\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendPushNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public $tries = 3;

    public $timeout = 120;
    public $backoff = [10, 20, 30];

    /**
     * Decide whether job should be queued
     */
    public function shouldQueue(NotificationCreated $event)
    {
        return (
            ($event->sendPush && !empty($event->notification->user_id))
            || !empty($event->notification->device_token)
        );
    }

    /**
     * Handle the queued listener
     */
    public function handle(NotificationCreated $event)
    {
        $notification = $event->notification;

        $context = [
            'notification_id' => $notification->id ?? null,
            'user_id' => $notification->user_id ?? null,
            'job_attempt' => $this->job?->attempts(),
        ];

        Log::info('SendPushNotification started', $context);

        $oneSignalSuccess = false;
        $fcmSuccess = false;

        // OneSignal
        try {
            $oneSignalSuccess = $this->maybeSendOneSignal($event, $context);
        } catch (Throwable $e) {
            Log::error('OneSignal push failed', $context + [
                    'channel' => 'onesignal',
                    'error' => $e->getMessage(),
                ]);
        }

        // FCM
        try {
            $fcmSuccess = $this->maybeSendFcm($notification, $context);
        } catch (Throwable $e) {
            Log::error('FCM push failed', $context + [
                    'channel' => 'fcm',
                    'error' => $e->getMessage(),
                ]);
        }

        /**
         * Retry only if BOTH channels failed
         * This prevents duplicate pushes.
         */
        if (!$oneSignalSuccess && !$fcmSuccess) {
            throw new \Exception('Both push channels failed.');
        }

        Log::info('SendPushNotification completed successfully', $context + [
                'onesignal_success' => $oneSignalSuccess,
                'fcm_success' => $fcmSuccess,
            ]);
    }

    /**
     * Send OneSignal push
     */
    private function maybeSendOneSignal(NotificationCreated $event, array $context): bool
    {
        if (!$event->sendPush || empty($event->notification->user_id)) {
            return false;
        }

        NotificationService::sendOneSignal($event->notification);

        Log::info('Push sent', $context + ['channel' => 'onesignal']);

        return true;
    }

    /**
     * Send FCM push
     */
    private function maybeSendFcm($notification, array $context): bool
    {
        if (empty($notification->device_token)) {
            return false;
        }

        NotificationService::sendFCM($notification);

        Log::info('Push sent', $context + ['channel' => 'fcm']);

        return true;
    }

    /**
     * Called when job permanently fails
     */
    public function failed(NotificationCreated $event, Throwable $exception)
    {
        Log::critical('SendPushNotification permanently failed', [
            'notification_id' => $event->notification->id ?? null,
            'user_id' => $event->notification->user_id ?? null,
            'error' => $exception->getMessage(),
            'attempts' => $this->job?->attempts(),
        ]);
    }
}
