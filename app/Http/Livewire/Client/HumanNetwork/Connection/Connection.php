<?php

namespace App\Http\Livewire\Client\HumanNetwork\Connection;

use App\Helpers\Helpers;
use App\Models\Admin\Alchemy\AlchemyCode;
use App\Models\Admin\Code\CodeDetail;
use App\Models\User;
use Livewire\Component;

class Connection extends Component
{
    public $search_connection_name, $connection_requests = [], $search_request_name, $per_page = 12,
        $style_feature_color_codes, $alchemy_color_codes, $style_code, $alchemy_code, $filter_alchemy_codes_array = [];

    public function loadMore(){

        return $this->per_page += $this->per_page;
    }

    public function updatingAlchemyCode($value){

        $this->filter_alchemy_codes_array = AlchemyCode::getNumbersFromCode($value);
    }

    public function updatingSearchConnectionName($value){

        $this->search_request_name = $value;

    }

    public function render()
    {

        $this->style_feature_color_codes = CodeDetail::styleAndFeatureCode();

        $this->alchemy_color_codes = CodeDetail::alchemyCodes();

        $users = User::allClients($this->search_connection_name, $this->per_page, $this->style_code, $this->filter_alchemy_codes_array);

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
