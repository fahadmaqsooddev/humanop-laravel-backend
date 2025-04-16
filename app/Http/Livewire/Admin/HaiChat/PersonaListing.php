<?php

namespace App\Http\Livewire\Admin\HaiChat;

use App\Models\HAIChai\HaiChatSetting;
use Livewire\Component;

class PersonaListing extends Component
{
    public $persona_id;

    public $personas = [];

    public function updatedPersonaId($value){

        $this->reset('persona_id');

        $this->persona_id = $value['0'];
    }

    public function render()
    {

        $this->personas = HaiChatSetting::whereNotNull('persona_name')->get();

        return view('livewire.admin.hai-chat.persona-listing');
    }

    public function viewEditPersona(){

        $this->emit('viewEditPersona', $this->persona_id);
    }

    public function createNewPersona(){

        $this->reset('persona_id');

        $this->emit('viewEditPersona', null);
    }
}
