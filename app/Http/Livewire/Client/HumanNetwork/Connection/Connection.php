<?php

namespace App\Http\Livewire\Client\HumanNetwork\Connection;

use App\Helpers\Helpers;
use App\Models\User;
use Livewire\Component;

class Connection extends Component
{
    public $users = [], $search_connection_name, $connection_requests = [], $search_request_name;

    public function updatingSearchConnectionName($value){

        $this->search_request_name = $value;

    }

    public function render()
    {

        $this->users = User::allClients($this->search_connection_name);

        $this->connection_requests = \App\Models\Client\Connection\Connection::connectionRequests($this->search_connection_name);

        return view('livewire.client.human-network.connection.connection');
    }

    public function connectUnConnectUser($id, $type){

        $data['friend_id'] = $id;

        $data['user_id'] = Helpers::getWebUser()->id;

        $data['type'] = $type;

        if ($id === $data['user_id']){

            return toastr()->error("You can't connect with yourself");
        }

        \App\Models\Client\Connection\Connection::connectUnConnect($data);
    }

    public function followUser($user_id){

        \App\Models\Client\Follow\Follow::addFollow($user_id);
    }
}
