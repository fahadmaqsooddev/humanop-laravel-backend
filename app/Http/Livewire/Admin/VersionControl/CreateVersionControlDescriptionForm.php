<?php

namespace App\Http\Livewire\Admin\VersionControl;
use App\Models\Admin\VersionControl\Version;
use App\Models\Admin\VersionControl\VersionControlDescription;
use Livewire\Component;

class CreateVersionControlDescriptionForm extends Component
{

    

    public $versions,$version_id,$description,$description_id;
    public $platform=[];
    protected $listeners = ['updateDescriptionValues','emptyVersionControlValues','updateContent'];
    protected $rules = [
        'version_id' => 'required',
        'description' => 'required',
        'platform'=>'required'
    ];

    protected $messages = [
        'version_id.required' => 'Title is required',
        'description.required' => 'Description is required',
        'platform.required' => 'Platform is required',
    ];

    

    public function updateContent($editorId, $data)
    {
        $this->description = $data;
    }

    public function updateDescriptionValues($id,$version_id,$description,$platform){
        // dd($id,$version_id,$description,$platform);
        
        $this->emptyVersionControlValues();
        $this->description_id = $id;
        $this->version_id = $version_id;
        $this->description = $description;
        // $this->platform = $platform;
        $this->platform = explode(',', $platform);
        // $this->emit('contentUpdated', $this->note);
    }

    public function emptyVersionControlValues(){
        $this->version_id = '';
        $this->description_id = '';
        $this->description = '';
        $this->platform = '';
    }

    public function updateDescription(){
        try {

            $validatedData = $this->validate();

            if($this->description_id){
            
                VersionControlDescription::editDescription($this->description_id,$this->version_id,$this->description,$this->platform);
                $this->emit('closeModal');
                $this->reset();
                $this->emit('refreshVersions');
                $this->emit('updateSession','Updated');
            }else{

       
                VersionControlDescription::createDescription($this->version_id,$this->description,$this->platform);
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
        $this->versions=Version::getVersions();
        return view('livewire.admin.version-control.create-version-control-description-form');
    }
}
