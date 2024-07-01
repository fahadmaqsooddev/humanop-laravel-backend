<?php

namespace App\Http\Livewire\Admin\SubAdmin;

use App\Models\User;
use Livewire\Component;

class Permission extends Component
{
    public $permission = [];
    public $adminId;

    public function updatePermission()
    {

        $admin = User::find($this->adminId);

        if ($admin) {
            $permissionsToUpdate = [];
            foreach ($this->permission as $key => $value) {
                $permission = \Spatie\Permission\Models\Permission::where('name',$value)->first();
                if ($value) {
                    $permissionsToUpdate[] = $permission->id;
                }
            }

            $admin->syncPermissions($permissionsToUpdate);

            session()->flash('success'.$this->adminId, 'Permissions updated successfully.');
        } else {
            session()->flash('error'.$this->adminId, 'Sub Admin not found.');
        }
    }


    public function render()
    {
        return view('livewire.admin.sub-admin.permission');
    }
}
