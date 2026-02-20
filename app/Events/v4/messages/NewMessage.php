<?php

namespace App\Events\v4\messages;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;


class NewMessage implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, InteractsWithQueue, SerializesModels;

    public string $broadcastQueue = 'broadcasts';

    protected int $thread_id;
    protected array $payload;

    public function __construct(
        int     $thread_id,
        int     $sender_id,
        int     $receiver_id,
        ?string $message = null,
        ?string $time = null
    )
    {
        $this->thread_id = $thread_id;

        $this->payload = [
            'thread_id' => $thread_id,
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'message' => $message,
            'time' => $time,
        ];
    }

    public function broadcastOn()
    {
        return new PrivateChannel(
            'chat.thread.' . $this->thread_id
        );
    }

    public function broadcastAs()
    {
        return 'message.simple';
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }
}
