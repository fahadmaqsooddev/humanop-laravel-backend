<?php

namespace App\Http\Livewire\Admin\SubAdmin;

use App\Events\SubAdminLogout;
use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class Index extends Component
{
    public $admins;
    public $statuses = [];
    protected $listeners=['deleteSubAdmin','refreshComponent' => '$refresh'];

    public function mount($admins)
    {
        $this->admins = $admins;
        foreach ($this->admins as $admin) {
            $this->statuses[$admin->id] = $admin->status == 1;
        }
        
    }
    public function updateStatus($id)
    {
          $admin = User::find($id);
        if ($admin) {
          if($admin->status == 1){
              User::updateUser(['status' => 0],$id);
              session()->flash('success'.$id, 'Sub Admin Status Changed to Inactive.');

              event(new SubAdminLogout($admin['id']));
              
          }else{
              User::updateUser(['status' => 1],$id);
              session()->flash('success'.$id, 'Sub Admin Status Changed to Active.');
          }
        } else {
            session()->flash('error'.$id, 'Sub Admin not found.');
        }
    }

    public function deleteSubAdmin($id){
    User::deleteSubAdmin($id); 
    $this->emit('refreshComponent');
    }


    public function render()
    {
        return view('livewire.admin.sub-admin.index');
    }
}
