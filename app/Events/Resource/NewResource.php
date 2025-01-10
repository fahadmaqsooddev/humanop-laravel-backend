<?php

namespace App\Events\Resource;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewResource implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */


    public $permission;
    public $heading;
    public $message;

    public function __construct($permission = null, $heading = null, $message = null)
    {
        $this->permission = $permission === '1' ? 'freemium' : ($permission === '2' ? 'core' : ($permission === '3' ? 'premium' : 'all'));
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
        return new Channel('push-notification.' . $this->permission);
    }

    public function broadcastAs()
    {

        return 'new.resource';
    }
}
