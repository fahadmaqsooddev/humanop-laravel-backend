<?php

namespace App\Models\Admin\Notification;

use App\Models\User;
use GuzzleHttp\Client;
use App\Helpers\Helpers;
use App\Enums\Admin\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function getNotification($notificationId = null)
    {
        return self::whereId($notificationId)->first();
    }

    public static function allNotification()
    {
        $user = Helpers::getUser();

        // $userPermission = match ($user['plan_name'] ?? '') {'Freemium' => 1, 'Core' => 2, 'Premium' => 3, default => 4,};

        return self::where('user_id', $user['id'])
            // ->where('permission', $userPermission)
            ->where('role', Admin::B2C_NOTIFICATION)
            // ->where('notification_priority', '!=', Admin::MESSAGE_SEND_NOTIFICATION)
            // ->where('notification_priority', '!=', Admin::NEW_MESSAGE_NOTIFICATION)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'type', 'message', 'created_at', 'read', 'notification_priority']);
    }
    // public static function allNotificationsMessages()
    // {
    //     $user = Helpers::getUser();

    //     $userPermission = match ($user['plan_name'] ?? '') {'Freemium' => 1, 'Core' => 2, 'Premium' => 3, default => 4,};

    //     return self::where('user_id', $user['id'])
    //         ->where('permission', $userPermission)
    //         ->where('role', Admin::B2C_NOTIFICATION)
    //         ->where('notification_priority', Admin::MESSAGE_SEND_NOTIFICATION)
    //         ->where('notification_priority', Admin::NEW_MESSAGE_NOTIFICATION)
    //         ->orderBy('created_at', 'desc')
    //         ->get(['id', 'type', 'message', 'created_at', 'read', 'notification_priority']);
    // }

    public static function allB2BNotification()
    {
        $user = Helpers::getUser();

        return self::where('user_id', $user['id'])
            ->where('role', Admin::B2B_NOTIFICATION)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'type', 'message', 'created_at', 'read', 'notification_priority']);
    }


    public static function createNotification($type, $message, $deviceToken = null, $userId = null, $permission = null, $priority = null, $role = null)
    {
        self::create([
            'user_id' => $userId,
            'type' => $type,
            'message' => $message,
            'device_token' => $deviceToken,
            'permission' => $permission,
            'notification_priority' => $priority,
            'role' => $role,
        ]);

        if ($deviceToken) {

            self::sendFCMNotification($type, $message, $deviceToken);

        }
    }

    protected static function sendFCMNotification($title, $body, $deviceToken)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = config('gumlet.gumlet_server_key');


        $data = [
            'to' => $deviceToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'default',
            ],
        ];


        try {

            $client = new Client();

            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'key=' . $serverKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ]);

            return $response->getBody()->getContents();

        } catch (\Exception $e) {

            \Log::error('FCM Error: ' . $e->getMessage());

            return false;
        }
    }

    public static function readNotification($notificationId = null)
    {
        return self::whereId($notificationId)->update(['read' => 1]);
    }

    public static function notReadNotification()
    {
        return self::where('read', 0)->get();
    }

    public static function deleteNotification($id = null)
    {
        $notification = self::whereId($id)->first();

        return $notification->delete();
    }
}
