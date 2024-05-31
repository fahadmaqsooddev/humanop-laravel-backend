<?php

namespace App\Http\Livewire\Admin\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Livewire\Component;
use Mockery\Expectation;

class PasswordSettingForm extends Component
{
    public $current_password,$password,$confirm_password;

    public function submitForm(){
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
