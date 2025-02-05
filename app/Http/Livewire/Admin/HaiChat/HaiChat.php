<?php

namespace App\Http\Livewire\Admin\HaiChat;

use App\Helpers\Helpers;
use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\ChatPrompt;
use App\Models\HAIChai\HaiChatSetting;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class HaiChat extends Component
{
    public $chats, $name, $description, $chatBot, $copyChatBotId;
    protected $listeners = ['deleteChatbot'];
    protected $rules = [
        'name' => 'required|max:30',
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

            session()->flash('success', "Chatbot created successfully.");

            $this->resetForm();

            $this->emit('closeModel');

            $this->emit('closeAlert');

        }catch (ValidationException $exception) {

            session()->flash('errors', $exception->validator->errors()->getMessages());

            $this->emit('closeAlert');

        }catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

            $this->emit('closeAlert');

        }
    }

    public function deleteChatbot($id)
    {
        $chat = Chatbot::singleChat($id);

        $aiReply = $this->sendRequestFromGuzzle('post', 'http://18.234.162.68:8000/delete-folder', ['folder_n' => $chat['name']]);

        if ($aiReply == 1)
        {

            Chatbot::deleteChat($id);

            session()->flash('success', "Chatbot deleted successfully.");

            $this->emit('closeAlert');

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

    public function showModalChatBotDetail($id){

        $this->chatBot = Chatbot::singleChat($id);
    }

    public function closeChatBotDetailModal(){

        $this->chatBot = null;
    }

    public function copyChatBot($id){

        $this->reset('name','description');

        $this->copyChatBotId = $id;
    }

    public function createDuplicateChatBot(){

        try {

            $this->validate();

            $chatBot = Chatbot::singleChat($this->copyChatBotId);

            if ($chatBot){

                $aiReply = $this->sendRequestFromGuzzle('post', 'http://18.234.162.68:8000/create-chatbot', ['vendor_n' => $this->name]);

                $newChatBot = Chatbot::createChat($aiReply, $this->description ?? $chatBot->description);

                ChatPrompt::duplicatingChatBot($chatBot->name, $aiReply);

                HaiChatSetting::duplicatingChatBotSetting($chatBot->id, $newChatBot->id);

                $this->reset('copyChatBotId','name','description');

                $this->emit('closeCopyChatBot');

            }

            session()->flash('success','Chatbot copied');

        }catch (ValidationException $exception){

            session()->flash('errors', $exception->validator->errors()->getMessages());

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function render()
    {
        $this->getChats();

        return view('livewire.admin.hai-chat.hai-chat', ['chats' => $this->chats]);
    }
}
