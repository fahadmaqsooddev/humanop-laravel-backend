<?php

namespace App\Events;

use App\Models\Admin\Notification\Notification;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class NotificationCreated
{
    use Dispatchable, SerializesModels;

    public Notification $notification;
    public bool $sendPush;

    public function __construct(Notification $notification, bool $sendPush = false)
    {

        $this->notification = $notification;

        $this->sendPush = $sendPush;

    }
}
