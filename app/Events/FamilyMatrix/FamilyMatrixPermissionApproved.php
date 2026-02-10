<?php

namespace App\Events\FamilyMatrix;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FamilyMatrixPermissionApproved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $requesterId;
    public $approverId;
    public $message;

    /**
     * Create a new event instance.
     *
     * @param int $requesterId
     * @param int $approverId
     * @param string $message
     */
    public function __construct(int $requesterId, int $approverId, string $message)
    {
        $this->requesterId = $requesterId;
        $this->approverId  = $approverId;
        $this->message     = $message; // Dynamic message from caller
    }

    /**
     * The channel the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Broadcast to the requester only
        return new Channel('push-notification.' . $this->requesterId);
    }

    /**
     * Event name on frontend
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'family.matrix.permission.approved';
    }

    /**
     * Payload sent to frontend
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'message'     => $this->message,
            'approved_by' => $this->approverId,
        ];
    }
}
