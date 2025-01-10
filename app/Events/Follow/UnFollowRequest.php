<?php

namespace App\Events\Follow;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UnFollowRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $data;
    public $friendId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($friendId=null,$heading=null,$message=null)
    {
        $this->friendId=$friendId;
        
        $this->data = [
            'friend_id' => $friendId,
            'heading'=>$heading,
            'message'=>$message
        ];
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
    
        return 'un-follow.request';
    }
}
