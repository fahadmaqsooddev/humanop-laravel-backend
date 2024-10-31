<?php

namespace App\Http\Controllers\ClientController;

use App\Http\Controllers\Controller;
use App\Services\TwilioServices\TwilioServices;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Twilio\Rest\Client;

class TwilioController extends Controller
{
    protected $twilioService;

    /**
     * UserController constructor.
     * @param UserServices $userServices
     */
    public function __construct(TwilioServices $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    public function sendNumberOtp(Request $request){
        $otp = Str::random(4);

        $to = $request->user_phone;

        $message = 'Your Human Op verification code is '.$otp;
//        $status = $this->twilioService->sendOtp($to, $message);

//        if($status != false) {
            Session::put(['two_way_auth' => ['id' => $request->user_id,'otp' => $otp]]);
            session()->flash('success', 'Verification Code Sent To Your Number Successfully');
            return redirect()->route('two.way.auth');
//        }else {
//
//            session()->flash('error', 'Something went wrong during sending code please resend it');
//            return redirect()->route('two.way.auth');
//        }
    }
}
