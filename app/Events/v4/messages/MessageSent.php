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
        // Ensure relationship is loaded
        $message->load('sender');

        $sender = $message->sender;

        $this->payload = [
            'id' => $message->id,
            'thread_id' => $message->message_thread_id,
            'sender' => $sender ? [
                'id' => $sender->id,
                'name' => trim($sender->first_name . ' ' . $sender->last_name),
                'photo_url' => $sender->photo_url,
            ] : null,
            'message_text' => $message->message,
            'upload_url' => $message->upload_url,
            'created_at' => $message->created_at,
        ];
    }

    public function broadcastOn()
    {
        return new PrivateChannel(
            'chat.thread.' . $this->payload['thread_id']
        );
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
