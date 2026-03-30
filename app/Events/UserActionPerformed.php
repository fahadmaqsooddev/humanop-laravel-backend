<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldQueue; // <-- Add this
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue; // <-- For queued events
use Illuminate\Support\Facades\Log;

class UserActionPerformed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, InteractsWithQueue;

    public $userId;
    public $action;
    public $details;

    /**
     * Create a new event instance.
     */
    public function __construct($userId, $action, $details = null)
    {
        $this->userId = $userId;
        $this->action = $action;
        $this->details = $details;

    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        $channelName = 'push-notification.' . $this->userId;

        return new Channel($channelName);
    }

    public function broadcastAs()
    {
        return 'user-action-performed';
    }

    public function broadcastWith()
    {
        $payload = [
            'user_id' => $this->userId,
            'action' => $this->action,
            'details' => $this->details,
            'time' => now()->toDateTimeString(),
        ];

        return $payload;
    }
}