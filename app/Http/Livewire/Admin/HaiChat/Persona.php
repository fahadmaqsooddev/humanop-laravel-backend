<?php

namespace App\Http\Livewire\Admin\HaiChat;

use App\Models\B2B\BusinessStrategies;
use App\Models\B2B\BusinessSubStrategies;
use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\ChatPrompt;
use App\Models\HAIChai\HaiChatSetting;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Persona extends Component
{
    public $chat_bot_id, $persona_text = null, $persona_name, $human_op_app, $maestro_app,
        $connected_human_apps = [], $client_companies = [], $industry_categories = [],
        $connected_maestro_apps = [], $first_time = true;

    protected $listeners = ['updateChatBotHumanApp','viewEditPersona','updateChatBotMaestroApp'];

    public function rules(){

        return [
            'persona_name' => 'required|max:50|unique:hai_chat_setting,persona_name,' . $this->chat_bot_id . ',chat_bot_id',
            'chat_bot_id' => 'required',
            'human_op_app' => 'nullable',
        ];
    }

    protected $messages = [
        'persona_name.unique' => 'Persona with same name already exists.',
    ];

//    public function mount($name){
//
//        $this->chat_bot_id = Chatbot::where('name', $name)->first()->id ?? null;
//    }

    public function updateOrSave(){

        try {

            $this->validate();

            HaiChatSetting::updatePersonaConfigurations($this->chat_bot_id, $this->persona_text, $this->persona_name, $this->human_op_app, $this->maestro_app);

            $this->emit('$refresh');

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
            $this->maestro_app =  $setting['maestro_app'] . '-' . $setting['maestro_app_id'];

        }
    }

    public function updateChatBotHumanApp($human_app){

        $this->human_op_app = $human_app;

        HaiChatSetting::where('human_op_app', $human_app)->update(['human_op_app' => 0]);
    }

    public function updateChatBotMaestroApp($maestro_app){

        $maestro_app_array = explode('-', $maestro_app);

        if (isset($maestro_app_array[1])){

            HaiChatSetting::where('maestro_app', $maestro_app_array[0])->where('maestro_app_id', $maestro_app_array[1])->update([
                'maestro_app' => 0,
                'maestro_app_id' => null,
            ]);

        }else{

            HaiChatSetting::where('maestro_app', $maestro_app_array[0])->update([
                'maestro_app' => 0,
                'maestro_app_id' => null,
            ]);
        }

    }

    public function viewEditPersona($id = null){

        $this->reset('persona_name', 'human_op_app');

        $this->chat_bot_id = HaiChatSetting::whereId($id)->first()->chat_bot_id ?? null;

    }

    public function render()
    {

        $this->connected_human_apps = HaiChatSetting::pluck('human_op_app')->unique()->toArray();

        $this->connected_maestro_apps = HaiChatSetting::whereNot('maestro_app', 0)->get()->map(function ($value){

            if ($value['maestro_app'] === 1){

                return (string)$value['maestro_app'];

            }else{

                return $value['maestro_app'] . '-' . $value['maestro_app_id'];
            }
        })->toArray();

        if ($this->chat_bot_id && empty($this->human_op_app)){

            $setting = HaiChatSetting::getHaiChatSetting($this->chat_bot_id);

//            $this->persona_text = $setting['persona_text'];
            $this->persona_name = $setting['persona_name'];
            $this->human_op_app = $setting['human_op_app'];
            $this->maestro_app = $setting['maestro_app_id'] ? $setting['maestro_app'] . '-' . $setting['maestro_app_id'] : $setting['maestro_app'];

        }

        if ($this->first_time){

            $this->client_companies = User::whereNotNull('company_name')->whereNot('company_name', "")->select(['id','company_name'])->get()->unique('company_name');

            $this->industry_categories = BusinessStrategies::all();

            $this->chatBots = Chatbot::get();

            $this->first_time = false;
        }

        return view('livewire.admin.hai-chat.persona');
    }
}
