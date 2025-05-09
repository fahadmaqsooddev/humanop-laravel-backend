<?php

namespace App\Events\B2B;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class B2BResetPassword implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $user_id;
    public $message;
    public function __construct($userId=null,$message=null)
    {
        //
        $this->user_id=$userId;
        $this->message=$message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('push-notification.' . $this->user_id);
    }

    public function broadcastAs()
    {
        return 'reset-password.' . $this->user_id;
    }


    public function broadcastWith()
    {
        return [
            'user_id' => $this->user_id,
            'message' => $this->message,
        ];

    }
}
