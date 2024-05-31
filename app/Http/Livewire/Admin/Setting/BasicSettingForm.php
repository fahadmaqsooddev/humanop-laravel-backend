<?php

namespace App\Http\Livewire\Admin\Setting;

use App\Models\User;
use Livewire\Component;

class BasicSettingForm extends Component
{
    public $currentUser, $ageRange;


    public function mount($user)
    {
        $this->currentUser = $user->toArray();
    }
    protected function rules()
    {
        return [
            'currentUser.first_name' => 'required|string|max:255',
            'currentUser.last_name' => 'required|string|max:255',
            'currentUser.email' => 'required|email|max:255|unique:users,email,' . $this->currentUser['id'],
            'currentUser.age_range' => 'required|regex:/^\d{1,2}-\d{1,2}$/',
            'currentUser.gender' => 'required|string',
            'currentUser.phone' => 'required|string|max:25',
        ];
    }
    protected function validationAttributes()
    {
        return [
            'currentUser.first_name' => 'first name',
            'currentUser.last_name' => 'last name',
            'currentUser.email' => 'email',
            'currentUser.age_range' => 'age range',
            'currentUser.gender' => 'gender',
            'currentUser.phone' => 'phone',
        ];
    }

    public function submitForm()
    {
        $this->validate();
        try {
            $age = explode('-', $this->currentUser['age_range']);
            $this->currentUser['age_min'] = $age[0];
            $this->currentUser['age_max'] = $age[1];

            $keysToKeep = ['first_name', 'last_name','email','age_min', 'age_max', 'gender', 'phone'];
            $data = array_intersect_key($this->currentUser, array_flip($keysToKeep));

            User::updateUser($data, $this->currentUser['id']);
            session()->flash('success', 'User updated successfully.');
        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.admin.setting.basic-setting-form');
    }
}
