<?php

namespace App\Events\Connection;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequestAccept implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $userId;
    public $heading;
    public $message;
    /**
     * Create a new event instance.
     *
     * @return void
     */
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

        return 'connection.accept';
    }
}
