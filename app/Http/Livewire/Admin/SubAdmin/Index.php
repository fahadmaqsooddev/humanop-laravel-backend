<?php

namespace App\Http\Livewire\Admin\SubAdmin;

use App\Events\SubAdminLogout;
use App\Http\Requests\Admin\Setting\CreateSubAdmin;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Traits\HandlesValidationErrors;


class Index extends Component
{
    public $admins, $sub_admin, $permission, $day, $month, $year, $statuses = [];

    protected $listeners = ['deleteSubAdmin', 'refreshComponent' => '$refresh'];

    use HandlesValidationErrors;

    public function mount($admins)
    {
        $this->admins = $admins;
        $this->year = 1980;
        $this->sub_admin['gender'] = '0';
        $this->sub_admin['is_admin'] = 3;

        foreach ($this->admins as $admin) {
            $this->statuses[$admin->id] = $admin->status == 1;
        }

    }

    public function submitForm()
    {

        $this->sub_admin['date_of_birth'] = $this->year . '-' . $this->month . '-' . $this->day;

        if ($this->customValidation(new CreateSubAdmin($this->sub_admin), $this->sub_admin)) {
            return;
        };

        try {

            DB::transaction(function () {

                $roleName = 'sub admin';

                $role = Role::firstOrCreate(['name' => $roleName]);

                $user = User::createSubAdmin($this->sub_admin);

                $user->assignRole($role);

                if (!empty($this->permission)){

                    foreach ($this->permission as $permissionName => $hasPermission) {

                        if ($hasPermission) {

                            $permission = Permission::firstOrCreate(['name' => $permissionName]);

                            $user->givePermissionTo($permission);

                        }

                    }

                }

                session()->flash('success', 'sub admin created successfully.');

                $this->emit('refreshPage');


            });

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }

    }

    public function updateStatus($id)
    {
        $admin = User::find($id);
        if ($admin) {
            if ($admin->status == 1) {
                User::updateUser(['status' => 0], $id);
                session()->flash('success' . $id, 'Sub Admin Status Changed to Inactive.');

                event(new SubAdminLogout($admin['id']));

            } else {
                User::updateUser(['status' => 1], $id);
                session()->flash('success' . $id, 'Sub Admin Status Changed to Active.');
            }
        } else {
            session()->flash('error' . $id, 'Sub Admin not found.');
        }
    }

    public function deleteSubAdmin($id)
    {
        User::deleteSubAdmin($id);
        $this->emit('refreshComponent');
    }


    public function render()
    {
        return view('livewire.admin.sub-admin.index');
    }
}
