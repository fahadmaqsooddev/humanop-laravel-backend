<?php

namespace App\Events\messages;

use App\Models\Client\Message\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public string $broadcastQueue = 'broadcasts';

    public function __construct(public Message $message)
    {

    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.thread.' . $this->message->message_thread_id);
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }

    public function broadcastWith(): array {

        return [
            'id' => $this->message->id,
            'thread_id' => $this->message->message_thread_id,
            'sender' => [
                'id' => $this->message->sender->id,
                'name' => $this->message->sender->first_name . ' ' .$this->message->sender->last_name,
                'photo_url' => $this->message->sender->photo_url->url
            ],
            'message_text' => $this->message->message,
//            'upload_url' => $this->message->upload_url,
            'created_at' => $this->message->created_at,
        ];
    }
}
