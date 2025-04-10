<?php

namespace App\Http\Livewire\Admin\HaiChat;

use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\ChatPrompt;
use App\Models\HAIChai\HaiChatSetting;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Persona extends Component
{
    public $chat_bot_id, $persona_text = null, $name, $persona_name, $human_op_app, $maestro_app, $connected_human_apps = [];

    protected $listeners = ['updateChatBotHumanApp'];

    protected $rules = [
        'persona_name' => 'required|max:50',
        'chat_bot_id' => 'required',
        'human_op_app' => 'nullable',
//        'maestro_app' => 'nullable'
    ];

    public function mount($name){

        $this->chat_bot_id = Chatbot::where('name', $name)->first()->id ?? null;

//        $setting = HaiChatSetting::getHaiChatSetting($this->chat_bot_id);
//
//        if ($setting){
//
//            $this->persona_text = $setting['persona_text'];
//            $this->persona_name = $setting['persona_name'];
//            $this->human_op_app = $setting['human_op_app'];
//            $this->maestro_app = $setting['maestro_app'];
//
//        }

    }

    public function updateOrSave(){

        try {

            $this->validate();

            HaiChatSetting::updatePersonaConfigurations($this->chat_bot_id, $this->persona_text, $this->persona_name, $this->human_op_app, $this->maestro_app);

            session()->flash('success', "Persona Updated");

        }catch (ValidationException $validationException){

            session()->flash('errors', $validationException->validator->errors()->getMessages());

        } catch (\Exception $exception){

            session()->flash('error', $exception->getMessage());
        }

        $this->emit('successMessage');
    }

    public function updatedChatBotId($value){

        $value = empty($value) ? null : $value;

        $this->emit('updateChatBotId', $value);

        $setting = HaiChatSetting::getHaiChatSetting($value);

        if ($setting){

            $this->persona_text = $setting['persona_text'];
            $this->persona_name = $setting['persona_name'];
            $this->human_op_app = $setting['human_op_app'];
            $this->maestro_app = $setting['maestro_app'];

        }

    }

    public function updateChatBotHumanApp($human_app){

        $this->human_op_app = $human_app;

        HaiChatSetting::where('human_op_app', $human_app)->update(['human_op_app' => 0]);
    }

    public function render()
    {
        $this->chatBots = Chatbot::get();

        $this->connected_human_apps = HaiChatSetting::pluck('human_op_app')->unique()->toArray();

//        $this->chat_bot_id = Chatbot::getChatFromVendorName($this->name)->id ?? null;

        if ($this->chat_bot_id && empty($this->human_op_app)){

            $setting = HaiChatSetting::getHaiChatSetting($this->chat_bot_id);

            $this->persona_text = $setting['persona_text'];
            $this->persona_name = $setting['persona_name'];
            $this->human_op_app = $setting['human_op_app'];
            $this->maestro_app = $setting['maestro_app'];
        }

        return view('livewire.admin.hai-chat.persona');
    }
}
