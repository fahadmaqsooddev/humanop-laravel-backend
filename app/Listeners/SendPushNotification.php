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

    // Use non-typed properties for maximum compatibility
    public $tries = 3;
    public $timeout = 120;
    public $backoff = 30;

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
            'notification_id' => $notification->id,
            'user_id' => $notification->user_id,
            'job_attempt' => $this->attempts(),
        ];

        Log::info('SendPushNotification started', $context);

        $hasError = false;

        // Try OneSignal
        try {
            $this->maybeSendOneSignal($event, $context);
        } catch (Throwable $e) {
            $hasError = true;
        }

        // Try FCM
        try {
            $this->maybeSendFcm($notification, $context);
        } catch (Throwable $e) {
            $hasError = true;
        }

        // If any channel failed → retry job
        if ($hasError) {
            throw new \Exception('One or more push channels failed.');
        }

        Log::info('SendPushNotification completed successfully', $context);
    }

    /**
     * Send OneSignal push
     */
    private function maybeSendOneSignal(NotificationCreated $event, array $context)
    {
        if (!$event->sendPush || empty($event->notification->user_id)) {
            return;
        }

        try {
            NotificationService::sendOneSignal($event->notification);

            Log::info('Push sent', $context + ['channel' => 'onesignal']);

        } catch (Throwable $e) {

            Log::error('OneSignal push failed', $context + [
                'channel' => 'onesignal',
                'error' => $e->getMessage(),
            ]);

            throw $e; // Allow retry
        }
    }

    /**
     * Send FCM push
     */
    private function maybeSendFcm($notification, array $context)
    {
        if (empty($notification->device_token)) {
            return;
        }

        try {
            NotificationService::sendFCM($notification);

            Log::info('Push sent', $context + ['channel' => 'fcm']);

        } catch (Throwable $e) {

            Log::error('FCM push failed', $context + [
                'channel' => 'fcm',
                'error' => $e->getMessage(),
            ]);

            throw $e; // Allow retry
        }
    }

    /**
     * Called when job fails permanently
     */
    public function failed(NotificationCreated $event, Throwable $exception)
    {
        Log::critical('SendPushNotification permanently failed', [
            'notification_id' => $event->notification->id,
            'user_id' => $event->notification->user_id,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts(),
        ]);
    }
}