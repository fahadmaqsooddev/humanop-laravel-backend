<?php

namespace App\Http\Livewire\Admin\VersionControl;

use App\Models\Admin\VersionControl\Version;
use Livewire\Component;

class VersionControlForm extends Component
{

    public $verionId, $version, $details;

    protected $rules = [
        'version' => 'required',
        'details' => 'required|max:2000',
    ];

    protected $messages = [
        'version.required' => 'Version is required',
        'details.required' => 'Version Details is required',
    ];

    public function updateEditModal($id, $version, $details)
    {
        $this->verionId = $id;
        $this->version = $version;
        $this->details= $details;

    }

    public function createVersion()
    {

        try {

            $this->validate();

            Version::createVersion($this->version, $this->details);

            session()->flash('success', "{$this->version} create successfully.");

            $this->emit('closeUpdateModal');

            $this->resetForm();

        }catch (\Exception $exception)
        {

            session()->flash('error', $exception->getMessage());

        }
    }

    public function updateVersion()
    {

        try {

            $this->validate();

            Version::editVersion($this->verionId,$this->version, $this->details);

            session()->flash('success', "{$this->version} Updated successfully.");

            $this->emit('closeUpdateModal');

            $this->resetForm();

        }catch (\Exception $exception)
        {

            session()->flash('error', $exception->getMessage());

        }
    }

    public function resetForm()
    {
        $this->reset(['version', 'details']);
    }

    public function render()
    {

        $versions = Version::getVersions();

        return view('livewire.admin.version-control.version-control-form', ['versions' => $versions]);
    }
}
