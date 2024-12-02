<?php

namespace App\Http\Livewire\Admin\HaiChat;

use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\HaiChatSetting;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class HaiChat extends Component
{
    public $chats, $name, $description;
    protected $listeners = ['deleteChatbot'];
    protected $rules = [
        'name' => 'required',
        'description' => 'required|max:2000',
    ];

    protected $messages = [
        'name.required' => 'Name is required',
        'description.required' => 'Information is required',
    ];

    public function submitForm()
    {

        try {

            $this->validate();

            $aiReply = $this->sendRequestFromGuzzle('post', 'http://18.234.162.68:8000/create-chatbot', ['vendor_n' => $this->name]);

            $chatbot = Chatbot::createChat($aiReply, $this->description);

            HaiChatSetting::updateHaiChatSetting(null,null,null,null,$chatbot->id);

            session()->flash('success', "{$this->name} created successfully.");

            $this->resetForm();

            $this->emit('closeModel');

        }catch (\Exception $exception)
        {

            session()->flash('error', $exception->getMessage());

        }
    }

    public function deleteChatbot($id)
    {
        $chat = Chatbot::singleChat($id);

        $aiReply = $this->sendRequestFromGuzzle('post', 'http://18.234.162.68:8000/delete-folder', ['folder_n' => $chat['name']]);

        if ($aiReply == 1)
        {

            Chatbot::deleteChat($id);

            session()->flash('success', "{$chat['name']} deleted successfully.");

        }

    }

    public function sendRequestFromGuzzle($method = null, $route_name = null, $body = [])
    {

        $authorization = Request::header('Authorization');

        $queryArray = [
            'headers' => ['Authorization' => $authorization],
            'json' => $body
        ];

        $client = new Client(['http_errors' => false, 'timeout' => 180]);

        $route = $route_name;

        $response = $client->request($method, $route, $queryArray);

        $response_body = json_decode($response->getBody()->getContents(), true);

        return $response_body;
    }

    public function resetForm()
    {
        $this->reset(['name', 'description']);
    }

    public function getChats()
    {
        $this->chats = Chatbot::allChats();
    }

    public function render()
    {
        $this->getChats();

        return view('livewire.admin.hai-chat.hai-chat', ['chats' => $this->chats]);
    }
}
