<?php

namespace App\Events\B2B;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotSharedDataWithBusiness implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $companyId;
    public $message;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($companyId = null, $message = null)
    {
        $this->companyId = $companyId;
        $this->message = $message;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('push-notification.' . $this->companyId);
    }

    public function broadcastAs()
    {
        return 'not-share-data.' . $this->companyId;
    }


    public function broadcastWith()
    {
        return [
            'company_id' => $this->companyId,
            'message' => $this->message,
        ];

    }
}
