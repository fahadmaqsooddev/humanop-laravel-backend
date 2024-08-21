<?php

namespace App\Http\Livewire\Client\ClientQuery;

use Livewire\Component;
use App\Models\HAIChai\ClientQuery as QueryModal;
use App\Helpers\Helpers;
use PHPUnit\Exception;

class ClientQuery extends Component
{

    public $query;
    public $chat;
    protected $listeners = ['clientQueryShow' => 'refreshClientQuery'];


    public function refreshClientQuery($chat)
    {
        $this->chat = $chat;
    }

    public function submitForm()
    {
        try {
            QueryModal::createQuery(Helpers::getWebUser()->id, $this->query);

            session()->flash('success', "Your Query submit successfully");
            $this->query = '';
            $this->emit('hideModal');
        }
        catch (Exception $exception)
        {
            session()->flash('error', $exception->getMessage());
        }
    }

    public function render()
    {
//        dd(1);
        return view('livewire.client.client-query.client-query');
    }
}
