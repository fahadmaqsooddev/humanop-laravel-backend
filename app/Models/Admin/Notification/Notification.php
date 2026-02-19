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

    public static function allB2CNotification()
    {
        $user = Helpers::getUser();

        return self::where('user_id', $user['id'])
            ->where('role', Admin::B2C_NOTIFICATION)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'type', 'message', 'created_at', 'read', 'notification_priority', 'sender_id']);
    }

    public static function allB2CMessageNotificationCount(): array
    {
        $user = Helpers::getUser();

        if (empty($user) || empty($user['id'])) {
            return [
                'all_notification_counts' => 0,
                'read_notification_counts' => 0,
                'unread_notification_counts' => 0,
                'message_notification_counts' => 0,
            ];
        }

        $baseQuery = self::query()
            ->where('user_id', $user['id'])
            ->where('role', Admin::B2C_NOTIFICATION);

        $allNotificationCounts = (clone $baseQuery)
            ->where('notification_priority', '!=', Admin::MESSAGE_SEND_NOTIFICATION)
            ->count();

        $unreadNotificationCounts = (clone $baseQuery)
            ->where('read', Admin::NOT_READ_NOTIFICATION)
            ->count();

        $readNotificationCounts = (clone $baseQuery)
            ->where('read', Admin::READ_NOTIFICATION)
            ->count();

        $messageNotificationCounts = (clone $baseQuery)
            ->where('notification_priority', Admin::MESSAGE_SEND_NOTIFICATION)
            ->count();

        return [
            'all_notification_counts' => $allNotificationCounts,
            'read_notification_counts' => $readNotificationCounts,
            'unread_notification_counts' => $unreadNotificationCounts,
            'message_notification_counts' => $messageNotificationCounts,
        ];
    }

    public static function allNetworkNotification()
    {
        $user = Helpers::getUser();

        return self::where('user_id', $user['id'])
            ->where('role', Admin::B2C_NOTIFICATION)
            ->whereIn('notification_priority', [4, 5, 6, 7, 9, 10])
            ->orderBy('created_at', 'desc')
            ->get(['id', 'type', 'message', 'created_at', 'read', 'notification_priority', 'sender_id']);
    }

    public static function allB2BNotification()
    {
        $user = Helpers::getUser();

        return self::where('user_id', $user['id'])
            ->where('role', Admin::B2B_NOTIFICATION)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'type', 'message', 'created_at', 'read', 'notification_priority']);
    }


    public static function createNotification($type, $message, $deviceToken = null, $userId = null, $permission = null, $priority = null, $role = null, $senderId = null)
    {
        self::create([
            'user_id' => $userId,
            'type' => $type,
            'message' => $message,
            'device_token' => $deviceToken,
            'permission' => $permission,
            'notification_priority' => $priority,
            'role' => $role,
            'sender_id' => $senderId,
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

            return false;
        }
    }

    public static function readNotification($notificationId = null)
    {
        return self::whereId($notificationId)->update(['read' => 1]);
    }

    public static function noReadNotification($notificationId = null)
    {
        return self::whereId($notificationId)->update(['read' => 0]);
    }

    public static function notReadNotification()
    {
        return self::where('read', 0)->get();
    }

    public static function deleteNotification($notificationIds = null)
    {
        if (!empty($notificationIds)) {

            self::whereIn('id', $notificationIds)->delete();

        } else {

            $allNotificationIds = self::allB2CNotification()->pluck('id')->toArray();

            if (!empty($allNotificationIds)) {

                self::whereIn('id', $allNotificationIds)->delete();

            }

        }

        return true;

    }

    public static function createUserFetchNotification($userId = null, $notifications = null)
    {

        foreach ($notifications as $notification) {

            $notification['user_id'] = $userId;

            self::create($notification);

        }

        return true;
    }

}
