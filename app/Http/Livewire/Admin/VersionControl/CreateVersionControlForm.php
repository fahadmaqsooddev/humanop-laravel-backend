<?php

namespace App\Http\Livewire\Admin\VersionControl;

use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Admin\VersionControl\Version;
use Livewire\Component;

class CreateVersionControlForm extends Component
{
    public $version,$note,$version_id;
    protected $listeners = ['updateVersionValues','emptyVersionControlValues','updateContent'];
    protected $rules = [
        'version' => 'required',
        'note' => 'required',
    ];

    protected $messages = [
        'version.required' => 'Title is required',
        'note.required' => 'Description is required',
    ];

    

    public function updateContent($editorId, $data)
    {
        $this->note = $data;
    }

    public function updateVersionValues($id,$title,$note){
        $this->emptyVersionControlValues();
        $this->version_id = $id;
        $this->version = $title;
        $this->note = $note;
        $this->emit('contentUpdated', $this->note);
    }

    public function emptyVersionControlValues(){
        $this->version_id = '';
        $this->version = '';
        $this->note = '';
    }

    public function updateVersion(){
        try {

            $validatedData = $this->validate();

            if($this->version_id){
                Version::editVersion($this->version_id,$this->version,$this->note);
                $this->emit('closeModal');
                $this->reset();
                $this->emit('refreshVersions');
                $this->emit('updateSession','Updated');
            }else{

            
                Version::createVersion($this->version,$this->note);
                $this->emit('closeModal');
                $this->reset();
                $this->emit('refreshVersionControl');
                $this->emit('updateSession','Created');
            }
        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.version-control.create-version-control-form');
    }
}
