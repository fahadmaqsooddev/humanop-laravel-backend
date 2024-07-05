<?php

namespace App\Http\Livewire\Client\Chat;

use Livewire\Component;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
class Index extends Component
{
    public $userMessage = '';
    public $messages = [];


    public function sendMessage(){
       if(isset($this->userMessage)){
           $this->messages[] = ['type' => 'user', 'text' => $this->userMessage];

           $aiReply = $this->sendRequestFromGuzzle('post',config('chat.api'),['query' => $this->userMessage]);


           $this->messages[] = ['type' => 'bot', 'text' => $aiReply];
           $this->emit('updateAiMessage');


           $this->userMessage = '';
       }
    }

    public function sendRequestFromGuzzle($method = null, $route_name = null, $body = []) {
        $authorization = Request::header('Authorization');
        $queryArray = [
            'headers' => ['Authorization' => $authorization],
            'json' => $body
        ];
        $client = new Client(['http_errors' => false, 'timeout' => 180]);
        $route = $route_name;
        $response = $client->request($method, $route, $queryArray);
//        $statusCode = $response->getStatusCode();
        $response_body = json_decode($response->getBody()->getContents(), true);
        return $response_body;
    }

    public function render() {
        return view('livewire.client.chat.index', ['messages' => $this->messages]);
    }
}
