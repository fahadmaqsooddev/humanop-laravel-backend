<?php

namespace App\Events\v4;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EnergyShieldState implements ShouldBroadcast
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $shield_percent;
    public $energy_pool_state;

    public function __construct($user_id, $shield_percent, $energy_pool_state)
    {
        $this->user_id = $user_id;
        $this->shield_percent = $shield_percent;
        $this->energy_pool_state = $energy_pool_state;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('energy-shield.' . $this->user_id);
    }

    public function broadcastAs()
    {
        return 'energyshieldstate';
    }
}
