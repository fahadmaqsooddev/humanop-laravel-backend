<?php

namespace App\Http\Livewire\Admin\HaiChat;

use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\ChatPrompt;
use App\Models\HAIChai\HaiChatSetting;
use Livewire\Component;

class Persona extends Component
{
    public $chatBots, $chat_bot_id, $persona_text = null;

    public function updatedChatBotId($value){

        $this->persona_text = HaiChatSetting::where('chat_bot_id',$value)->first()->persona_text ?? null;
    }

    public function savePersona(){

        HaiChatSetting::where('chat_bot_id', $this->chat_bot_id)->update(['persona_text' => (!empty($this->persona_text) ? $this->persona_text : null)]);

        $this->emit('successMessage');
    }

    public function render()
    {
        $this->chatBots = Chatbot::get();

        if (!$this->chat_bot_id){

            $this->updatedChatBotId($this->chatBots[0]['id'] ?? null);

            $this->chat_bot_id = $this->chatBots[0]['id'] ?? null;
        }

        return view('livewire.admin.hai-chat.persona');
    }
}
