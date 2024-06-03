<?php

namespace App\Http\Livewire\Admin\Setting;
use App\Http\Requests\Admin\Setting\PasswordSettingRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\HandlesValidationErrors;
use App\Models\User;
use Livewire\Component;


class PasswordSettingForm extends Component
{
    use HandlesValidationErrors;
    public $current_password,$password,$confirm_password;

    public function submitForm(){
        $data = [
            'current_password' => $this->current_password,
            'password' => $this->password,
            'confirm_password' => $this->confirm_password
        ];

        if($this->customValidation(new PasswordSettingRequest($data),$data)){return;};

        try{
       $passCheck = User::checkPassword($this->current_password, Auth::user()->id);
       if($passCheck){

           User::updateUser(['password' => Hash::make($this->password)],Auth::user()->id);
           session()->flash('success', 'Password Changed Successfully!');
       }else{
           session()->flash('error', 'Current Password Not Matched');
          }
        }
       catch (\Expectation $e){
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.setting.password-setting-form');
    }
}
