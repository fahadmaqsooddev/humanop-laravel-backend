<?php

namespace App\Http\Livewire\B2b\B2bOrganizations;

use App\Models\B2B\B2BBusinessCandidates;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class B2bOrganization extends Component
{
    use WithPagination;
    protected $users;
    public $name, $email;
    public $perPage = 10;

    protected $listeners = ['resetPassword','deleteB2BAdminProfile'];

    public function resetPassword($id, $newPassword)
{
    if (strlen($newPassword) < 8) {
        $this->dispatchBrowserEvent('swal:error', ['message' => 'Password must be at least 8 characters long.']);
        return;
    }

    $user = User::B2BResetPassword($id, $newPassword);
    if($user){

        $this->dispatchBrowserEvent('swal:success', ['message' => 'Password has been reset successfully.']);
    }else{
        $this->dispatchBrowserEvent('swal:error', ['message' => 'Password Not Reset.']);
        return;
    }

}
 public function deleteB2BAdminProfile($businessId){
    
     B2BBusinessCandidates::deleteB2BAdmin($businessId);
 }





    public function render()
    {
        $users = User::getB2BAdmin($this->name, $this->email, $this->perPage);
        $users->withPath(url('admin/b2b-organizations'));

        return view('livewire.b2b.b2b-organizations.b2b-organization', [
            'users' => $users
        ]);
    }
}
