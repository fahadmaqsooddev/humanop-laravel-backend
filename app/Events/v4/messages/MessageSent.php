<?php

namespace App\Events\v4\messages;

use App\Models\v4\Client\Message\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public string $broadcastQueue = 'broadcasts';

    protected array $payload;

    public function __construct(Message $message)
    {

        $message->load('sender');

        $this->payload = [
            'id' => $message->id,
            'thread_id' => $message->message_thread_id,
            'sender' => [
                'id' => $message->sender->id,
                'name' => $message->sender->first_name . ' ' . $message->sender->last_name,
                'photo_url' => $message->sender->photo_url,
            ],
            'message_text' => $message->message,
            'upload_url' => $message->upload_url,
            'created_at' => $message->created_at?->toISOString(),
        ];

    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.thread.' . $this->payload['thread_id']);
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }
}
