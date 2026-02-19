<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendGroupRequest implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ownerId;
    public $heading;
    public $message;

    public string $broadcastQueue = 'broadcasts';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($ownerId = null, $heading = null, $message = null)
    {

        $this->ownerId = $ownerId;
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

        return new Channel('push-notification.' . $this->ownerId);
    }

    public function broadcastAs()
    {

        return 'send.groupRequest';
    }

    public function broadcastWith()
    {

        return [
            'owner_id' => $this->ownerId,
            'heading' => $this->heading,
            'message' => $this->message,
        ];

    }
}
