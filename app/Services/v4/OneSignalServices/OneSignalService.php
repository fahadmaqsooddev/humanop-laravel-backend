<?php

namespace App\Services\v4\OneSignalServices;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use App\Models\Admin\Notification\Notification;

class OneSignalService
{
    protected static function client(): Client
    {

        return new Client([

            'base_uri' => 'https://api.onesignal.com/',

            'timeout' => 10,

        ]);

    }

    protected static function headers(): array
    {

        return [
            'Authorization' => 'Key ' .  config('services.oneSignal.auth_key') ,
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ];

    }

    public static function createClient(string $id, string $email): ?array
    {

        try {

            $response = self::client()->post(

                "apps/" . config('services.oneSignal.app_id') . "/users",
                [
                    'headers' => self::headers(),
                    'json' => [
                        'identity' => [
                            'external_id' => $id,
                            'email' => $email,
                        ]
                    ]
                ]
            );

            return json_decode($response->getBody()->getContents(), true);

        } catch (GuzzleException $e) {

            Log::error('OneSignal Create Client Error: ' . $e->getMessage());

            return null;

        }

    }

    public static function sendNotification(?string $userId, string $heading, string $message, bool $sendToAll = false): bool
    {
        try {

            $payload = [
                'app_id' => config('services.oneSignal.app_id'),
                'headings' => ['en' => $heading],
                'contents' => ['en' => $message],
            ];

            // =============================================
            // SEND TO ALL USERS
            // =============================================

            if (!$sendToAll && !$userId) {

                return false;

            }

            if ($sendToAll) {

                $payload['included_segments'] = ['All'];

                self::client()->post('notifications?c=push', [
                    'headers' => self::headers(),
                    'json' => $payload
                ]);

                return true;
            }

            // =============================================
            // SEND TO SPECIFIC USER
            // =============================================
            $response = self::client()->get("apps/" . config('services.oneSignal.app_id') . "/users/by/external_id/{$userId}",
                [
                    'headers' => self::headers()
                ]
            );

            $data = json_decode($response->getBody()->getContents(), true);

            if (empty($data['subscriptions'])) {

                return false;

            }

            $badgeCount = Notification::notReadNotification()->count();

            foreach ($data['subscriptions'] as $subscription) {

                $payload['include_player_ids'] = [$subscription['id']];

                $payload['ios_badgeType'] = 'Set';

                $payload['ios_badgeCount'] = $badgeCount;

                self::client()->post('notifications?c=push', [
                    'headers' => self::headers(),
                    'json' => $payload
                ]);

            }

            return true;

        } catch (GuzzleException $e) {

            Log::error('OneSignal Send Notification Error: ' . $e->getMessage());

            return false;

        }

    }

}
