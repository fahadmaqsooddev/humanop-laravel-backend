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
    public $version, $note, $version_id;
    public $versionDetails = [
        ['type' => [], 'description' => '']
    ];


    protected $listeners = ['updateVersionValues', 'emptyVersionControlValues', 'updateContent'];
    protected $rules = [
        'version' => 'required',
        'note' => 'required',
    ];

    protected $messages = [
        'version.required' => 'Title is required',
        'note.required' => 'Description is required',
    ];

    public function addVersionField()
    {
        $this->versionDetails[] = ['type' => [], 'description' => ''];
    }

    public function removeVersionField($index)
    {
        unset($this->versionDetails[$index]);
        $this->versionDetails = array_values($this->versionDetails); // reindex
    }



    public function updateContent($editorId, $data)
    {
        $this->note = $data;
    }

    public function updateVersionValues($id, $title, $note)
    {
        $this->emptyVersionControlValues();
        $this->version_id = $id;
        $this->version = $title;
        $this->note = $note;
        $this->emit('contentUpdated', $this->note);
    }

    public function emptyVersionControlValues()
    {
        $this->version_id = '';
        $this->version = '';
        $this->note = '';
        $this->versionDetails = [
            ['type' => [], 'description' => '']
        ];
    
    }

    public function storeVersionAndDescription()
{
    if ($this->version_id) {
        Version::editVersion($this->version_id, $this->version);
        $this->emit('closeModal');
        $this->emptyVersionControlValues();
        $this->emit('refreshVersions');
        $this->emit('updateSession', 'Updated');
    } else {
        $version = Version::createVersion($this->version, $this->note);

        foreach ($this->versionDetails as $detail) {
            VersionControlDescription::createDescription($version->id, $detail['description'], $detail['type']);
        }

        User::updateVersion();
        event(new VersionUpdate('New Version Is Added Please Update It'));

        $this->emit('closeModal');
        $this->emptyVersionControlValues();
        $this->emit('refreshVersionControl');
        $this->emit('updateSession', 'Created');
    }
}



    public function render()
    {
        return view('livewire.admin.version-control.create-version-control-form');
    }
}
