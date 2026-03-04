<?php

namespace App\Services\v4\NotificationService;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\Admin\Notification\Notification;
use App\Services\v4\OneSignalServices\OneSignalService;

class NotificationService
{
    /**
     * Send FCM push notification.
     */
    public static function sendFCM(Notification $notification): void
    {
        if (empty($notification->device_token)) {
            throw new \Exception('FCM device token missing');
        }

        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = config('services.fcm.server_key');

        $data = [
            'to' => $notification->device_token,
            'notification' => [
                'title' => $notification->type,
                'body'  => $notification->message,
                'sound' => 'default',
            ],
        ];

        $client = new \GuzzleHttp\Client(['http_errors' => true]);
        $response = $client->post($url, [
            'headers' => [
                'Authorization' => 'key=' . $serverKey,
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
        ]);

        $statusCode = $response->getStatusCode();
        $body = (string) $response->getBody();

        // Log::info('FCM API Response', [
        //     'status_code' => $statusCode,
        //     'body' => $body,
        // ]);

        if ($statusCode !== 200) {
            throw new \Exception("FCM response not OK: $statusCode - $body");
        }
    }

    /**
     * Send OneSignal push notification.
     */
    public static function sendOneSignal(Notification $notification): void
    {
        if (empty($notification->user_id)) {
            return;
        }

        try {
            OneSignalService::sendNotification(
                $notification->user_id,
                $notification->type,
                $notification->message
            );
        } catch (\Exception $e) {
            Log::error('OneSignal Push failed: ' . $e->getMessage());
        }
    }
}