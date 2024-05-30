<?php

namespace App\Http\Livewire;

use App\Models\User;

use Livewire\Component;

class BasicSettingForm extends Component
{
    public $currentUser, $ageRange;


    public function mount($user)
    {
        $this->currentUser = $user->toArray();
    }

    public function submitForm()
    {
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
        return view('livewire.basic-setting-form');
    }
}
