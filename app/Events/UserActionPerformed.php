<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserActionPerformed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $userId,
        public string $action,
        public ?array $details = null
    ) {}

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
        return [
            'user_id' => $this->userId,
            'action' => $this->action,
            'details' => $this->filterDetails($this->details),
            'time' => now()->toISOString(), // better for frontend
        ];
    }

    private function filterDetails(?array $details): array
    {
        if (empty($details)) {
            return [];
        }

        return array_filter($details, fn ($v) => !is_null($v));
    }
}
