<?php

namespace App\Http\Livewire\Admin\VersionControl;

use App\Models\Admin\VersionControl\Version;
use Livewire\Component;
use Livewire\WithPagination;

class VersionControlForm extends Component
{

    use WithPagination;

    public $search = '';
    protected $versions;
    public    $perPage = 10;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];
    protected $listeners = ['refreshVersions','updateSession'];
    public $description;

    public function refreshVersions(){
        $this->getVersions();
    }

    public function getVersions()
    {
        $this->versions = Version::allVersions()->paginate($this->perPage);
    }

    public function editVersion($id,$version,$description){
        $this->emit('updateVersionValues', $id, $version, $description);
    }

    public function updateSession($type){
        session()->flash('success', 'Version '.$type.' successfully.');
    }


    public function render()
    {
        $this->getVersions();
        return view('livewire.admin.version-control.version-control-form', ['versions' => $this->versions]);
    }
}
