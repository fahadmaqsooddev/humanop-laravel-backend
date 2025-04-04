<?php

namespace App\Events\Assessment;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubmitAssessment implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $userId;
    public $currentPage;

    public function __construct($userId = null, $currentPage = null)
    {
        $this->userId = $userId;
        $this->currentPage = $currentPage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new Channel('push-notification.' . $this->userId);
    }
    public function broadcastAs(){

        return 'submit.assessment';
    }
    public function broadcastWith()
    {
        return [
            'user_id' => $this->userId,
            'current_page' => $this->currentPage,
        ];


    }
}
