<?php

namespace App\Http\Livewire\Client\Setting;

use App\Helpers\Helpers;
use App\Models\User;
use Livewire\Component;

class TwoWayAuth extends Component
{
    public $numberCheck = false;
    public $phone,$status;
    public $firstRender = true;

    public function validatePhone(){
        $validatedData = $this->validate([
            'phone' => 'required|string|min:4|max:14'
        ]);
    }

    public function updateStatus(){
        $this->firstRender = false;
       if(Helpers::getWebUser()['phone']){
           if(Helpers::getWebUser()['two_way_auth'] == 2){
               User::updateUser(['two_way_auth' => 1], Helpers::getWebUser()['id']);
               session()->flash('success', '2 Way Auth Activated Successfully.');
           }else{
               User::updateUser(['two_way_auth' => 2], Helpers::getWebUser()['id']);
               session()->flash('success', '2 Way Auth Disabled Successfully.');
           }
           $this->numberCheck = false;
       }else{
           session()->flash('error', 'Please Enter A Number For Verification.');
            $this->numberCheck = true;
       }
    }

    public function updateNumber(){
            $this->firstRender = false;
            $this->validatePhone();
            User::updateUser(['phone' => $this->phone, 'two_way_auth' => 1], Helpers::getWebUser()['id']);
            session()->flash('success', '2 Way Auth Activated Successfully.');
            $this->numberCheck = false;
    }

    public function render()
    {
        if($this->firstRender){
            $this->phone = Helpers::getWebUser()['phone'];
            $this->status = Helpers::getWebUser()['two_way_auth'] == 1 ? true : false;
        }

        return view('livewire.client.setting.two-way-auth');
    }
}
