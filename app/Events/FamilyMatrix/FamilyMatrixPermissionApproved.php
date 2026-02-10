<?php

namespace App\Events\FamilyMatrix;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FamilyMatrixPermissionApproved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $requesterId;
    public $approverId;
    public $message;
    public $status;

    /**
     * Create a new event instance.
     *
     * @param int $requesterId
     * @param int $approverId
     * @param string $message
     * @param int $status
     */
    public function __construct(int $requesterId, int $approverId, string $message, int $status = 1)
    {
        $this->requesterId = $requesterId;
        $this->approverId  = $approverId;
        $this->message     = $message;
        $this->status      = $status;

        Log::info('FamilyMatrixPermission event fired', [
            'requester_id' => $this->requesterId,
            'approver_id'  => $this->approverId,
            'message'      => $this->message,
            'status'       => $this->status,
        ]);
    }

    /**
     * The channel the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {

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
            'message'      => $this->message,
            'approved_by'  => $this->approverId,
            'status'       => $this->status,
        ];
    }
}
