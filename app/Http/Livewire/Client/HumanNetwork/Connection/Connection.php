<?php

namespace App\Http\Livewire\Client\HumanNetwork\Connection;

use App\Helpers\Helpers;
use App\Models\User;
use Livewire\Component;

class Connection extends Component
{
    public $search_connection_name, $connection_requests = [], $search_request_name, $per_page = 12;

    public function loadMore(){

        return $this->per_page += $this->per_page;
    }

    public function updatingSearchConnectionName($value){

        $this->search_request_name = $value;

    }

    public function render()
    {

        $users = User::allClients($this->search_connection_name, $this->per_page);

        $this->connection_requests = \App\Models\Client\Connection\Connection::connectionRequests($this->search_connection_name);

        return view('livewire.client.human-network.connection.connection', ['users' => $users]);
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
