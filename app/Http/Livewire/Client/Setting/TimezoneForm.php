<?php

namespace App\Http\Livewire\Client\Setting;

use App\Helpers\Helpers;
use App\Models\User;
use Livewire\Component;

class TimezoneForm extends Component
{

    public $timezone;

    public function submitForm()
    {
        try {
            $user = Helpers::getWebUser();

            User::editTimezone($user['id'], $this->timezone);

            session()->flash('success', 'timezone create successfully');

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }
    }

    public function render()
    {
        $timezones = Helpers::timeZone();

        $user = Helpers::getWebUser();

        return view('livewire.client.setting.timezone-form', ['timezones' => $timezones, 'user' => $user]);
    }
}
