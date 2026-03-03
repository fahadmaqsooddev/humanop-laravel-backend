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
    public static function sendFCM(Notification $notification): bool
    {
        if (empty($notification->device_token)) {
            return false;
        }

        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = config('gumlet.gumlet_server_key');

        $data = [
            'to' => $notification->device_token,
            'notification' => [
                'title' => $notification->type,
                'body'  => $notification->message,
                'sound' => 'default',
            ],
        ];

        try {
            $client = new Client();
            $client->post($url, [
                'headers' => [
                    'Authorization' => 'key=' . $serverKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => $data,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('FCM Push failed: ' . $e->getMessage());
            return false;
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