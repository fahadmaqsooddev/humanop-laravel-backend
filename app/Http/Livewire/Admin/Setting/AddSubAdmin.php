<?php

namespace App\Http\Livewire\Admin\Setting;

use App\Http\Requests\Admin\Setting\CreateSubAdmin;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Traits\HandlesValidationErrors;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AddSubAdmin extends Component
{
    public $sub_admin,$permission, $day, $month, $year;
    use HandlesValidationErrors;
    public function mount()
    {
//        $this->sub_admin['age_range'] = '5-6';
        $this->sub_admin['gender'] = '0';
        $this->sub_admin['is_admin'] = 3;
    }
    public function submitForm()
    {

        $this->sub_admin['date_of_birth'] = $this->year . '-' . $this->month . '-' . $this->day;

        if($this->customValidation(new CreateSubAdmin($this->sub_admin),$this->sub_admin)){return;};
        try {
            DB::transaction(function(){
                $roleName = 'sub admin';
                $role = Role::firstOrCreate(['name' => $roleName]);
                $user = User::createSubAdmin($this->sub_admin);
                $user->assignRole($role);
                foreach ($this->permission as $permissionName => $hasPermission) {
                    if ($hasPermission) {
                        $permission = Permission::firstOrCreate(['name' => $permissionName]);
                        $user->givePermissionTo($permission);
                    }
                }
                session()->flash('success', 'sub admin created successfully.');
            });
        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.admin.setting.add-sub-admin');
    }
}
