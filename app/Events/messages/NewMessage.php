<?php

namespace App\Events\messages;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $sender_id;
    public $receiver_id;
    public $user;
    public $message;
    public $time;
    public function __construct($sender_id=null,$receiver_id=null,$user=null,$message=null,$time=null)
    {
        $this->sender_id=$sender_id;
        $this->receiver_id=$receiver_id;
        $this->user=$user;
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
        return new Channel('messages'.$this->sender_id.'-'.$this->receiver_id);
    }
    public function broadcastAs(){
    
        return 'sent.message';
    }

    public function broadcastWith()
    {
        return [
            'sender_id' => $this->sender_id,
            'receiver_id' => $this->receiver_id,
            'user' => $this->user,
            'message' => $this->message,
            'time' => $this->time,
        ];

        
    }
}
