<?php

namespace App\Events\FamilyMatrix;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FamilyMatrixPermission implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $targetId;
    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct($userId, $targetId, $message)
    {
        $this->userId   = $userId;
        $this->targetId = $targetId;
        $this->message  = $message;

        Log::info('FamilyMatrixPermission event fired', [
            'requested_by' => $this->userId,
            'target_id'    => $this->targetId,
            'message'      => $this->message,
        ]);
    }

    /**
     * Broadcast channel (public)
     */
    public function broadcastOn()
    {
        return new Channel('push-notification.' . $this->targetId);
    }

    /**
     * Broadcast event name
     */
    public function broadcastAs()
    {
        return 'family.matrix.permission.requested';
    }

    /**
     * Payload sent to frontend
     */
    public function broadcastWith()
    {
        return [
            'requested_by' => $this->userId,
            'target_id'    => $this->targetId,
            'message'      => $this->message,
        ];
    }
}
