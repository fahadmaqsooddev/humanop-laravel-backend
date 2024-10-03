<?php

namespace App\Http\Livewire\Admin\Setting;

use App\Models\User;
use Livewire\Component;
use App\Traits\HandlesValidationErrors;
use App\Http\Requests\Admin\Setting\BasicSettingRequest;
class BasicSettingForm extends Component
{
    use HandlesValidationErrors;
    public $currentUser, $ageRange, $day, $month, $year;


    public function mount($user)
    {
        $this->currentUser = $user->toArray();

        $date_of_birth = explode('-', $this->currentUser['date_of_birth']);

        $this->day = isset($date_of_birth[2]) ? intval($date_of_birth[2]) : 0;

        $this->month = isset($date_of_birth[1]) ? intval($date_of_birth[1]) : 0;

        $this->year = isset($date_of_birth[0]) && !empty($date_of_birth[0]) ? intval($date_of_birth[0]) : 1980;
    }


    public function submitForm()
    {

        $this->currentUser['date_of_birth'] = $this->year . '-' . $this->month . '-' . $this->day;

        if($this->customValidation(new BasicSettingRequest($this->currentUser),$this->currentUser)){return;};

        try {

            $keysToKeep = ['first_name','last_name','email','date_of_birth','gender','phone'];

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
