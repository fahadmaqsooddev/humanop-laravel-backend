<?php

namespace App\Http\Livewire\Admin\HaiChat;

use App\Models\HAIChai\HaiChatSetting;
use Livewire\Component;

class PersonaListing extends Component
{
    public $persona_id;

    public $personas = [];

    protected $listeners = ['$refresh'];

    public function updatedPersonaId($value){

        $this->reset('persona_id');

        $this->persona_id = $value['0'];
    }

    public function render()
    {

        $this->personas = HaiChatSetting::has('chatbot')->whereNotNull('persona_name')->with('chatbot')->get();

        return view('livewire.admin.hai-chat.persona-listing');
    }

    public function viewEditPersona($persona_id){

        $this->persona_id = $persona_id;

        $this->emit('viewEditPersona', $persona_id);
    }

    public function createNewPersona(){

        $this->reset('persona_id');

        $this->emit('viewEditPersona', null);
    }

    public function deletePersona($id){

        HaiChatSetting::whereId($id)->update([
            'maestro_app' => 0,
            'maestro_app_id' => null,
            'persona_name' => null,
            'human_op_app' => 0,
        ]);

    }
}
