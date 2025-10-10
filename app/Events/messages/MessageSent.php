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

    /**
     * Create a new event instance.
     *
     * @return void
     */
//    public $friendId;
//    public $message;
//    public $time;
//    public $heading;

//    public function __construct($friendId = null, $message = null, $time = null, $heading = null)
//    {
//
//        $this->friendId=$friendId;
//        $this->message=$message;
//        $this->time=$time;
//        $this->heading = $heading;
//    }

    public function __construct(public Message $message)
    {

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

//    public function broadcastOn()
//    {
//        return new Channel('push-notification.' . $this->friendId);
//    }

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
            ],
            'message_text' => $this->message->message,
//            'upload_id' => $this->message->upload_id,
            'created_at' => $this->message->created_at,
        ];
    }
}
