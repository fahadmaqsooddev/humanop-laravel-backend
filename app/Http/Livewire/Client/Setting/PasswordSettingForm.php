<?php

namespace App\Http\Livewire\Client\Setting;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\HandlesValidationErrors;
use App\Models\User;
use App\Http\Requests\Admin\Setting\PasswordSettingRequest;
use Livewire\Component;
use App\Helpers\Helpers;

class PasswordSettingForm extends Component
{

    use HandlesValidationErrors;

    public $current_password, $password, $confirm_password;

    public function submitForm()
    {
        $webUser = Helpers::getWebUser();
        $data = [
            'password' => $this->password,
            'confirm_password' => $this->confirm_password
        ];

        // Add current_password to data if google_id is empty and password_set is 2
        if ($webUser->google_id == '' && $webUser->password_set == 2) {
            $data['current_password'] = $this->current_password;
        }

        if ($this->customValidation(new PasswordSettingRequest($data), $data)) {
            return;
        }

        try {
            // Check current password if needed
            if (isset($data['current_password'])) {
                $passCheck = User::checkPassword($this->current_password, $webUser->id);

                if (!$passCheck) {
                    session()->flash('error', 'Current Password Not Matched');
                    return;
                }
            }

            // Update password
            User::updateUser(['password' => Hash::make($this->password)], $webUser->id);
            session()->flash('success', 'Password Changed Successfully!');

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.client.setting.password-setting-form');
    }
}
