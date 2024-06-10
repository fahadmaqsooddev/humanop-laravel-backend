<?php

namespace App\Http\Livewire\Client\Setting;

use Livewire\Component;
use App\Models\User;
use App\Traits\HandlesValidationErrors;
use App\Http\Requests\Admin\Setting\BasicSettingRequest;

class BasicSettingForm extends Component
{
    
    use HandlesValidationErrors;

    public $user, $ageRange;


    public function mount($user)
    {
        $this->user = $user->toArray();
    }

    public function submitForm()
    {

        if($this->customValidation(new BasicSettingRequest($this->user),$this->user)){return;};

        try {

            $age = explode('-', $this->user['age_range']);
            $this->user['age_min'] = $age[0];
            $this->user['age_max'] = $age[1];

            $keysToKeep = ['first_name', 'last_name','email','age_min', 'age_max', 'gender', 'phone'];
            $data = array_intersect_key($this->user, array_flip($keysToKeep));

            User::updateUser($data, $this->user['id']);

            session()->flash('success', 'User updated successfully.');

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }
    }
    
    public function render()
    {
        return view('livewire.client.setting.basic-setting-form');
    }
}
