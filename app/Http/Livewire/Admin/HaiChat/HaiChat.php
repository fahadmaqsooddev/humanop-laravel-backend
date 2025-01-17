<?php

namespace App\Http\Livewire\Admin\HaiChat;

use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\HaiChatSetting;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class HaiChat extends Component
{
    public $chatBots, $name, $description, $chatBot;

    protected $listeners = ['deleteChatBot'];

    protected $rules = [
        'name' => 'required|max:30',
        'description' => 'required|max:900',
    ];

    protected $messages = [
        'name.required' => 'Name is required',
        'description.required' => 'Information is required',
    ];

    public function submitForm()
    {

        try {

            $this->validate();

            Chatbot::createChatBot($this->name, $this->description);

            session()->flash('success', "Chat Bot created successfully.");

            $this->resetForm();

            $this->emit('closeModel');

        }catch (ValidationException $exception) {

            session()->flash('errors', $exception->validator->errors()->getMessages());

        }catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());
        }

        $this->emit('closeAlert');
    }

    public function deleteChatBot($id)
    {

        Chatbot::deleteChatBot($id);

        session()->flash('success', "Chat Bot deleted successfully.");

        $this->emit('closeAlert');

    }

    public function resetForm()
    {
        $this->reset(['name', 'description']);
    }

    public function showModalChatBotDetail($id){

        $this->chatBot = Chatbot::singleChatBot($id);
    }

    public function closeChatBotDetailModal(){

        $this->chatBot = null;
    }

    public function connectUnConnectChatBot($chat_bot_id){

        $chat_bot = Chatbot::whereId($chat_bot_id)->first();

        if ($chat_bot){

            if ($chat_bot->is_connected === 1){ // already connect then disconnect it

                $chat_bot->update(['is_connected' => 0]);

            }else{

                $connectedChatBotCount = Chatbot::where('plan_id', $chat_bot->plan_id)->where('is_connected', 1)->count();

                if ($connectedChatBotCount === 0){

                    $chat_bot->update(['is_connected' => 1]);
                }

            }

        }

    }

    public function render()
    {

        $this->chatBots = Chatbot::allChatBots();

        return view('livewire.admin.hai-chat.hai-chat');
    }
}
