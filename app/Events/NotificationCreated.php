<?php

namespace App\Events;

use App\Models\Admin\Notification\Notification;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log; // ✅ Import Log


class NotificationCreated
{
    use Dispatchable, SerializesModels;

    public Notification $notification;
    public bool $sendPush;

    public function __construct(Notification $notification, bool $sendPush = false)
    {
        $this->notification = $notification;
        $this->sendPush = $sendPush;

        // Log::info('NotificationCreated event fired.', [
        //     'notification_id' => $notification->id ?? null,
        //     'user_id' => $notification->user_id ?? null,
        //     'type' => $notification->type ?? null,
        //     'message' => $notification->message ?? null,
        //     'device_token' => $notification->device_token ?? null,
        //     'role' => $notification->role ?? null,
        //     'priority' => $notification->notification_priority ?? null,
        //     'sender_id' => $notification->sender_id ?? null,
        //     'send_push' => $sendPush, // ✅ log the flag
        // ]);
    }
}