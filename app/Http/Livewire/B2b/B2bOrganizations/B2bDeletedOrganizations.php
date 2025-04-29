<?php

namespace App\Http\Livewire\B2b\B2bOrganizations;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class B2bDeletedOrganizations extends Component
{

    use WithPagination;
    public $perPage=10;
    public $name,$age,$email;
    protected $listeners=['restoreB2BAdmin','deleteB2BAdmin'];

    public function restoreB2BAdmin($userId){
     
        User::whereId($userId)->restore();
    
    }
    public function deleteB2BAdmin($userId){
     
     
        User::onlyTrashed()->whereId($userId)->forceDelete();
    
    }
    public function render()
    {
        $users=User::getB2BDeletedAdmins($this->name,$this->email,$this->age,$this->perPage);
        
        return view('livewire.b2b.b2b-organizations.b2b-deleted-organizations',[
            'users'=>$users
        ]);
    }
}
