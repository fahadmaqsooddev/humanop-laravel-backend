<?php

namespace App\Models\Admin\Notification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class Notification extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    public static function getNotification($notificationId = null)
    {
        return self::whereId($notificationId)->first();
    }

    public static function allNotification()
    {
        return self::orderBy('created_at', 'desc')->get(['id', 'type', 'message', 'created_at', 'read']);
    }

    public static function createNotification($type, $message, $deviceToken = null, $userId = null, $permission = null)
    {
        self::create([
            'user_id' => $userId,
            'type' => $type,
            'message' => $message,
            'device_token' => $deviceToken,
            'permission' => $permission,
        ]);

        if ($deviceToken) {

            $response = self::sendFCMNotification($type, $message, $deviceToken);

            return $response;
        }

        return true;
    }

    protected static function sendFCMNotification($title, $body, $deviceToken)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = env('FCM_SERVER_KEY');


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

            dd($response->getBody()->getContents());

            return $response->getBody()->getContents();

        } catch (\Exception $e) {


            dd($e->getMessage());

            \Log::error('FCM Error: ' . $e->getMessage());

            return false;
        }
    }

    public static function readNotification($notificationId = null)
    {
        return self::whereId($notificationId)->update(['read' => 1]);
    }
}
