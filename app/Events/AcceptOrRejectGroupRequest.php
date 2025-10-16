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

class AcceptOrRejectGroupRequest implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $memberId;
    public $heading;
    public $message;

    public string $broadcastQueue = 'broadcasts';


    /**
     * Create a new event instance.
     *
     * @return void
     */

    public function __construct($memberId = null, $heading = null, $message = null)
    {

        $this->memberId = $memberId;
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

        return new Channel('push-notification.' . $this->memberId);
    }

    public function broadcastAs()
    {

        return 'acceptReject.groupRequest';
    }

    public function broadcastWith()
    {

        return [
            'member_id' => $this->memberId,
            'heading' => $this->heading,
            'message' => $this->message,
        ];

    }

}
