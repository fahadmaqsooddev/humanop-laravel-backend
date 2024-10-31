<?php

namespace App\Http\Livewire\Client\Verification;

use App\Helpers\Helpers;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class PhoneVerification extends Component
{
    public $otp;

    public function verifyOtp(){
       if(Session::has('two_way_auth')){
           if($this->otp == Session::get('two_way_auth.otp')){
               return redirect()->route('login_to_dashboard',['id' => Session::get('two_way_auth.id')]);
           }else{
               session()->flash('error','Otp Not Matched Please Try Again');
           }
       } else{
           session()->flash('error','Something went wrong during sending code please resend it');
       }
    }

    public function resendOtp(){
        $status = Helpers::sendNumberOtp(Session::get('two_way_auth.phone'));
        if($status){
            session()->flash('success', 'Verification Code Sent To Your Number Successfully');
        }else{
            session()->flash('error', 'Something Went Wrong During Sending Code');
        }
    }


    public function render()
    {

        return view('livewire.client.verification.phone-verification');
    }
}
