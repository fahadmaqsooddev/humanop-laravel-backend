<?php

namespace App\Events\messages;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $friendId;
    public $message;
    public $time;
    public function __construct($friendId=null,$message=null,$time=null)
    {
        
        $this->friendId=$friendId;
        $this->message=$message;
        $this->time=$time;
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
    public function broadcastAs(){
    
        return 'message.sent';
    }

    public function broadcastWith()
    {
        return [
            'friend_id' => $this->friendId,
            'message' => $this->message,
            'time' => $this->time,
        ];

        
    }
}
