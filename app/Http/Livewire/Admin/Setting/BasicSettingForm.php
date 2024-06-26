<?php

namespace App\Http\Livewire\Admin\Setting;

use App\Models\User;
use Livewire\Component;
use App\Traits\HandlesValidationErrors;
use App\Http\Requests\Admin\Setting\BasicSettingRequest;
class BasicSettingForm extends Component
{
    use HandlesValidationErrors;
    public $currentUser, $ageRange;


    public function mount($user)
    {
        $this->currentUser = $user->toArray();
    }


    public function submitForm()
    {
        if($this->customValidation(new BasicSettingRequest($this->currentUser),$this->currentUser)){return;};
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
