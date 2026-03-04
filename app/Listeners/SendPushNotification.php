<?php

namespace App\Listeners;

use App\Events\NotificationCreated;
use App\Services\v4\NotificationService\NotificationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

class SendPushNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public int $tries = 3;
    public int $timeout = 120;
    public int $backoff = 30;

    public function shouldQueue(NotificationCreated $event): bool
    {
        return (
            ($event->sendPush && !empty($event->notification->user_id))
            || !empty($event->notification->device_token)
        );
    }

    public function handle(NotificationCreated $event): void
    {
        $notification = $event->notification;

        $context = [
            'notification_id' => $notification->id,
            'user_id' => $notification->user_id,
            'job_attempt' => $this->attempts(),
        ];

        Log::info('SendPushNotification started', $context);

        $this->maybeSendOneSignal($event, $context);
        $this->maybeSendFcm($notification, $context);

        Log::info('SendPushNotification completed', $context);
    }

    private function maybeSendOneSignal(NotificationCreated $event, array $context): void
    {
        if (!$event->sendPush || empty($event->notification->user_id)) {
            return;
        }

        try {
            NotificationService::sendOneSignal($event->notification);

            Log::info('Push sent', $context + ['channel' => 'onesignal']);

        } catch (Throwable $e) {

            Log::error('OneSignal push failed', $context + [
                'error' => $e->getMessage(),
                'channel' => 'onesignal',
            ]);

            throw $e; // Allow retry
        }
    }

    private function maybeSendFcm($notification, array $context): void
    {
        if (empty($notification->device_token)) {
            return;
        }

        try {
            NotificationService::sendFCM($notification);

            Log::info('Push sent', $context + ['channel' => 'fcm']);

        } catch (Throwable $e) {

            Log::error('FCM push failed', $context + [
                'error' => $e->getMessage(),
                'channel' => 'fcm',
            ]);

            throw $e; // Allow retry
        }
    }

    public function failed(NotificationCreated $event, Throwable $exception): void
    {
        Log::critical('SendPushNotification permanently failed', [
            'notification_id' => $event->notification->id,
            'user_id' => $event->notification->user_id,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts(),
        ]);
    }
}