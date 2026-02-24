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

    public static function allB2CNotification($read = null, $pagination = false, $perPage = null)
    {
        $user = Helpers::getUser();

        $query = self::where('user_id', $user['id'])
            ->where('role', Admin::B2C_NOTIFICATION);

        if ($read !== null && in_array($read, [0, 1])) {
            $query->where('read', $read);
        }

        $query->orderBy('created_at', 'desc')
            ->select([
                'id',
                'type',
                'message',
                'created_at',
                'read',
                'notification_priority',
                'sender_id'
            ]);

        // 👇 Call existing helper
        return Helpers::pagination($query, $pagination, $perPage);
    }

    public static function allB2CMessageNotificationCount(): array
    {
        $user = Helpers::getUser();

        if (empty($user) || empty($user['id'])) {
            return [
                'all_notification_counts'     => 0,
                'read_notification_counts'    => 0,
                'unread_notification_counts'  => 0,
                'message_notification_counts' => 0,
            ];
        }

        $counts = self::query()
            ->where('user_id', $user['id'])
            ->where('role', Admin::B2C_NOTIFICATION)
            ->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN `read` = ? THEN 1 ELSE 0 END) as read_count,
            SUM(CASE WHEN `read` = ? THEN 1 ELSE 0 END) as unread_count,
            SUM(CASE WHEN notification_priority = ? THEN 1 ELSE 0 END) as message_count
        ", [
                Admin::NOTIFICATION_STATUS_READ,
                Admin::NOTIFICATION_STATUS_UNREAD,
                Admin::MESSAGE_SEND_NOTIFICATION
            ])
            ->first();

        return [
            'all_notification_counts'     => (int) ($counts->total ?? 0),
            'read_notification_counts'    => (int) ($counts->read_count ?? 0),
            'unread_notification_counts'  => (int) ($counts->unread_count ?? 0),
            'message_notification_counts' => (int) ($counts->message_count ?? 0),
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
