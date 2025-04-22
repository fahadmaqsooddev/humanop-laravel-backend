<?php

namespace App\Events\B2B;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequestAccessData implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

     

    public $companyId;
    public $message;
    public $userId;
    public function __construct($companyId = null, $message = null,$userId=null)
    {
        $this->companyId = $companyId;
        $this->message = $message;
        $this->userId = $userId;

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

    public function broadcastAs()
    {
        return 'request-data.' . $this->companyId;
    }
    public function broadcastWith()
    {
        return [
            'company_id' => $this->companyId,
            'message' => $this->message,
            'user_id'=>$this->userId,
        ];

    }
}
