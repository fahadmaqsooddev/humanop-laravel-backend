<?php

namespace App\Http\Livewire\Admin\NewHaiChat;

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
            'maestro_app' => 'nullable'
        ];
    }

    protected $messages = [
        'persona_name.unique' => 'Persona with same name already exists.',
    ];

    public function updateOrSave(){

        try {

            $this->validate();

            ChatPrompt::createOrUpdatePersona($this->chat_bot_id,$this->persona_name,$this->human_op_app, $this->maestro_app);

            $this->emit('$refresh');

            session()->flash('success', "Persona Updated");

        }catch (ValidationException $validationException){

            session()->flash('errors', $validationException->validator->errors()->getMessages());

        } catch (\Exception $exception){

            session()->flash('error', $exception->getMessage());
        }

    }

    public function updatedChatBotId($value){

        $value = empty($value) ? null : $value;

        $this->emit('updateChatBotId', $value);

        $persona = ChatPrompt::singlePersona($value);

        if ($persona){

            $this->persona_text = $persona['persona_text'];
            $this->persona_name = $persona['persona_name'];
            $this->human_op_app = $persona['human_op_app'];
            $this->maestro_app =  $persona['maestro_app'] . '-' . $persona['maestro_app_id'];

        }
    }

    public function updateChatBotHumanApp($human_app){

        $this->human_op_app = $human_app;

        ChatPrompt::where('human_op_app', $human_app)->update(['human_op_app' => 0]);
    }

    public function updateChatBotMaestroApp($maestro_app){

        $maestro_app_array = explode('-', $maestro_app);

        if (isset($maestro_app_array[1])){

            ChatPrompt::where('maestro_app', $maestro_app_array[0])->where('maestro_app_id', $maestro_app_array[1])->update([
                'maestro_app' => 0,
                'maestro_app_id' => null,
            ]);

        }else{

            ChatPrompt::where('maestro_app', $maestro_app_array[0])->update([
                'maestro_app' => 0,
                'maestro_app_id' => null,
            ]);
        }

    }

    public function viewEditPersona($id = null){

        $this->reset('persona_name', 'human_op_app');

        $this->chat_bot_id = ChatPrompt::whereId($id)->first()->chat_bot_id ?? null;

    }

    public function render()
    {
        $persona = ChatPrompt::query();

        $this->connected_human_apps = $persona->pluck('human_op_app')->unique()->toArray();

        $this->connected_maestro_apps = $persona->whereNot('maestro_app', 0)->get()->map(function ($value){

            if ($value['maestro_app'] === 1){

                return (string)$value['maestro_app'];

            }else{

                return $value['maestro_app'] . '-' . $value['maestro_app_id'];
            }
        })->toArray();

        if ($this->chat_bot_id && empty($this->human_op_app)){

            $personaSetting = ChatPrompt::singlePersona($this->chat_bot_id);

            if ($personaSetting){

                $this->persona_name = $personaSetting['persona_name'] ?? null;
                $this->human_op_app = $personaSetting['human_op_app'] ?? null;
                $this->maestro_app = $personaSetting['maestro_app_id'] ? $personaSetting['maestro_app'] . '-' . $personaSetting['maestro_app_id'] : $personaSetting['maestro_app'];

            }else{

                $this->reset('persona_name','human_op_app','maestro_app');
            }

        }

        if ($this->first_time){

            $this->client_companies = User::whereNotNull('company_name')->whereNot('company_name', "")->select(['id','company_name'])->get()->unique('company_name');

            $this->industry_categories = BusinessStrategies::all();

            $this->chatBots = Chatbot::get();

            $this->first_time = false;
        }

        return view('livewire.admin.new-hai-chat.persona');
    }
}
