<?php

namespace App\Events\v4\DailyTip;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewDailyTip implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $userId;
    public $heading;
    public $message;

    public function __construct($userId = null, $heading = null, $message = null)
    {
        $this->userId = $userId;
        $this->heading = $heading;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('push-notification.' . $this->userId);
    }

    public function broadcastAs(){

        return 'new.dailyTip';
    }
}
