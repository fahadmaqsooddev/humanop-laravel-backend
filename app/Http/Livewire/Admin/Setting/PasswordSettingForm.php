<?php

namespace App\Http\Livewire\Admin\Setting;
use App\Http\Requests\Admin\Setting\PasswordSettingRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\HandlesValidationErrors;
use App\Models\User;
use Livewire\Component;
use App\Helpers\Helpers;

class PasswordSettingForm extends Component
{
    use HandlesValidationErrors;
    public $current_password,$password,$confirm_password;

    public function submitForm()
    {
        $webUser = Helpers::getWebUser();
        $data = [
            'password' => $this->password,
            'confirm_password' => $this->confirm_password
        ];

        // Add current_password to data if password_set is 1
        if ($webUser->password_set == 1) {
            $data['current_password'] = $this->current_password;
        }

        if ($this->customValidation(new PasswordSettingRequest($data), $data)) {
            return;
        }

        try {
            // Check current password if password_set is 1
            if ($webUser->password_set == 1) {
                $passCheck = User::checkPassword($this->current_password, $webUser->id);

                if (!$passCheck) {
                    session()->flash('error', 'Current Password Not Matched');
                    return;
                }
            }

            // Update password and set password_set to 1 if it's currently 2
            $updateData = ['password' => $this->password];
            if ($webUser->password_set == 2) {
                $updateData['password_set'] = 1;
            }

            User::updateUser($updateData, $webUser->id);

            session()->flash('success', 'Password Changed Successfully!');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.setting.password-setting-form');
    }
}
