<?php

namespace App\Http\Livewire\Admin\VersionControl;

use App\Events\Version\VersionUpdate;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Admin\VersionControl\Version;
use Livewire\Component;
use App\Models\Admin\VersionControl\VersionControlDescription;
use App\Models\User;


class CreateVersionControlForm extends Component
{
    public $version, $note;

    // for edit data
    public $versionId = null;
    public $description_id = null;
    public $data = null;

    public $versionDetails = [
        ['type' => [], 'description' => '', 'version_heading' => '']
    ];


    protected $listeners = ['updateVersionValues', 'emptyVersionControlValues', 'updateContent','updateNote', 'updateDescription'
,'updateEditorContent'];
    // protected $listeners = [];


    // protected $rules = [
    //     'version' => 'required|unique:version_control,version,version_id,' . $this->versionId, // Exclude current version with a custom primary key
    //     'note' => 'required',
    // ];
    
    

    protected $messages = [
        'version.required' => 'The version name is required.',
        'version.unique' => 'The version name has already been taken.',
        // 'note.required' => 'The note is required.',
    ];

    public function updateEditorContent($payload)
{
    dd($payload); // 🔍 This will show what JS is sending
}


 

    public function updateNote($value)
{
    $this->note = $value;
}

public function updateDescription($index, $value)
{
    $this->versionDetails[$index]['description'] = $value;
    // dd($this->versionDetails['description']);

}

    public function addVersionField()
    {
        $this->versionDetails[] = ['type' => [], 'description' => '', 'version_heading' => ''];
    }

    public function removeVersionField($index)
    {
        unset($this->versionDetails[$index]);
        $this->versionDetails = array_values($this->versionDetails); // reindex
    }





    public function mount($versionId = null)
    {

        $this->versionId = $versionId;

        if ($this->versionId) {
            $version = Version::getSingleVersion($versionId); 
            $this->version = $version->version; 
            $this->note = $version->note; 

            if (!empty($version['versionDescriptions']) && count($version['versionDescriptions']) > 0) {
                $this->versionDetails = [];


                foreach ($version['versionDescriptions'] as $item) {
                    $this->versionDetails[] = [
                        'id' => $item['id'],
                        'type' => explode(',', $item->platform),
                        'description' => $item->description,
                        'version_heading' => $item->version_heading
                    ];
                }
            } else {

                $this->versionDetails[] = [

                    'type' => [],
                    'description' => '',
                    'version_heading' => ''
                ];
            }
        }
    }






    public function updateContent($editorId, $data)
    {
        $this->note = $data;
    }



    public function emptyVersionControlValues()
    {
        $this->versionId = '';
        $this->description_id = '';
        $this->version = '';
        $this->note = '';
        $this->versionDetails = [
            ['type' => [], 'description' => '']
        ];
    }

    public function storeVersionAndDescription()
    {



        $this->validate([
            'version' => 'required|unique:version_control,version,' . $this->versionId, 
            // 'note' => 'required',
        ]);
     
        if ($this->versionId) {
        
            Version::editVersion($this->versionId, $this->version);

            foreach ($this->versionDetails as $detail) {
                if (!empty($detail['id'])) {

                    VersionControlDescription::editDescription(
                        $detail['id'],
                        $this->versionId,
                        $detail['description'],
                        $detail['type'],
                        $detail['version_heading']
                    );
                } else {

                    VersionControlDescription::createDescription(
                        $this->versionId,
                        $detail['description'],
                        $detail['type'],
                        $detail['version_heading']
                    );
                }
            }

            $this->emit('closeModal');
            $this->emptyVersionControlValues();
            $this->emit('refreshVersions');
            $this->emit('updateSession', 'Updated');
            session()->flash('success', 'Version updated successfully.');

        } else {
            // dd($)
            // dd($this->version,$this->note);
            $version = Version::createVersion($this->version, $this->note);

            foreach ($this->versionDetails as $detail) {
                
                VersionControlDescription::createDescription($version->id, $detail['description'], $detail['type'], $detail['version_heading']);
            }

            User::updateVersion();
            event(new VersionUpdate('New Version Is Added Please Update It'));

            $this->emit('closeModal');
            $this->emptyVersionControlValues();
            $this->emit('refreshVersionControl');
            $this->emit('updateSession', 'Created');
            session()->flash('success', 'Version Create successfully.');

        }
    }



    public function render()
    {
        return view('livewire.admin.version-control.create-version-control-form');
    }
}
