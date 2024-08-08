<?php

namespace App\Http\Livewire\Client\HumanNetwork\Follow;

use App\Models\Client\Follow\Follow;
use Livewire\Component;

class FollowFollowing extends Component
{

    public $followers = [], $followings = [], $follower_search;

    public function render()
    {

        $this->followers = Follow::followers($this->follower_search);

        $this->followings = Follow::following($this->follower_search);

        return view('livewire.client.human-network.follow.follow-following');
    }

    public function followUser($user_id){

        \App\Models\Client\Follow\Follow::addFollow($user_id);
    }
}
