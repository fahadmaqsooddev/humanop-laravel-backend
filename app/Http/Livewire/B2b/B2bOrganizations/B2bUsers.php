<?php

namespace App\Http\Livewire\B2b\B2bOrganizations;

use App\Models\B2B\B2BBusinessCandidates;
use Livewire\Component;
use Livewire\WithPagination;

class B2bUsers extends Component
{
 

    use WithPagination;
    public $perpage=2;
    public $businnesId;
    public $prefer;
    protected $listeners=['deleteClientProfile','FutureConsiderationClientProfile'];
    
    public function mount($business_id, $prefer)
    {
        $this->businnesId = $business_id;
        // dd($this->data);
        $this->prefer = $prefer;
    }


    public function deleteClientProfile($businessId,$candidateId){
        B2BBusinessCandidates::deleteUserFromBuisness($businessId,$candidateId);
    }
    public function FutureConsiderationClientProfile($businessId,$candidateId){
        B2BBusinessCandidates::futureConsiderationUserFromBuisness($businessId,$candidateId);
    }

    public function render()
    {
        $data=B2BBusinessCandidates::getBusinessUsers($this->businnesId,$this->prefer,$this->perpage);

        return view('livewire.b2b.b2b-organizations.b2b-users',compact('data'));
    }
}
