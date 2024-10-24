<?php

namespace App\Http\Livewire\Admin\Setting;

use App\Helpers\Helpers;
use App\Models\User;
use Livewire\Component;

class ChangeTimezoneForm extends Component
{

    public $timezone;

    public function mount()
    {
        $user = Helpers::getWebUser();

        $this->timezone = $user['timezone'];

    }

    public function submitTimezoneForm()
    {
        try {

            User::updateUserTimezone($this->timezone);

            session()->flash('success', 'timezone create successfully');

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }
    }

    public function render()
    {
        $timezones = Helpers::timeZone();

        return view('livewire.admin.setting.change-timezone-form', ['timezones' => $timezones]);
    }
}
