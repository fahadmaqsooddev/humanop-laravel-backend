<?php

namespace App\Events;

use App\Models\Admin\Notification\Notification;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationCreated
{
    use Dispatchable, SerializesModels;

    public $notification;
    public $sendPush;

    public function __construct(Notification $notification, $sendPush = false)
    {
        $this->notification = $notification;
        $this->sendPush = $sendPush;
    }
}