<?php

namespace App\Http\Livewire\Admin\VersionControl;

use App\Models\Admin\VersionControl\Version;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Admin\VersionControl\VersionControlDescription;


class VersionControlForm extends Component
{

    use WithPagination;

    public $search = '';
    protected $versions;
    public    $perPage = 10;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];
    protected $listeners = ['refreshVersions','updateSession','deleteVersionPermanently','deleteDescriptionPermanently'];
    public $note;

    public function refreshVersions(){
        $this->getVersions();
    }

    public function getVersions()
    {
        // $this->versions = Version::allVersions()->paginate($this->perPage);
        $this->versions = Version::allVersions();
        
    }

    public function editVersion($id,$version,$note){
        
        $this->emit('updateVersionValues', $id, $version, $note);
    }
    public function editDescription($id,$version_id,$description,$platform,$versionHeading){
        
        $this->emit('updateDescriptionValues', $id,$version_id,$description, $platform,$versionHeading);
    }

    public function updateSession($type){
        session()->flash('success', 'Version '.$type.' successfully.');
    }

    public function deleteVersionPermanently($id){
        
        Version::deleteVersion($id);
        session()->flash('success', 'Version Deleted successfully.');

    }
    public function deleteDescriptionPermanently($id){
        
        VersionControlDescription::deleteDescription($id);
        session()->flash('success', 'Description Deleted successfully.');

    }


    public function render()
    {
        $this->getVersions();
        
        return view('livewire.admin.version-control.version-control-form', ['versions' => $this->versions]);
    }
}
