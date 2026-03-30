<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserActionPerformed implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $userId,
        public string $action,
        public ?array $details = null
    ) {
      
        
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel("user-actions.{$this->userId}");
    }

    public function broadcastAs(): string
    {
        return 'user-action-performed';
    }

    public function broadcastWith(): array
    {
        $payload = [
            'user_id' => $this->userId,
            'action' => $this->action,
            'details' => $this->details,
            'time' => now()->toDateTimeString(),
        ];

        return $payload;
    }

    public function broadcastQueue(): string
    {
        return 'user-actions';
    }
}