<?php

namespace App\Events\v4\Connection;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConnectionRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $friendId;
    public $heading;
    public $message;

    public function __construct($friendId = null, $heading = null, $message = null)
    {
        $this->friendId = $friendId;
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
        return new Channel('push-notification.' . $this->friendId);
    }

    public function broadcastAs()
    {

        return 'connection.request';
    }

    public function broadcastWith()
    {
        return [
            'friend_id' => $this->friendId,
            'heading' => $this->heading,
            'message' => $this->message,
        ];


    }
}
