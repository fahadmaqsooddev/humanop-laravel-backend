<?php

namespace App\Http\Controllers\GoogleAuth;

use App\Http\Controllers\Controller;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Client\Dashboard\ActionPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\Helpers;

class GoogleController extends Controller
{

    public function redirectToGoogle($slug = null, $slug2 = null)
    {

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback($slug = null, $slug2 = null)
    {
        try {

            dd($slug, $slug2);

            $user = Socialite::driver('google')->user();

            if (!empty($slug) && !empty($slug2))
            {
                $practitioner = User::where('first_name', $slug)->where('last_name', $slug2)->first('id');

                $finduser = User::where('practitioner_id', $practitioner['id'])->orWhere('google_id', $user->id)->orWhere('email', $user->email)->first();
            }
            else
            {
                $finduser = User::where('google_id', $user->id)->orWhere('email', $user->email)->first();
            }

            if($finduser){

                Auth::login($finduser);

            }else{

                $name = explode(' ', $user->name);

                $data_array = [
                    'google_id' => $user->id,
                    'first_name' => $name[0] ?? "",
                    'last_name' => $name[1] ?? "",
                    'email' => $user->email,
                ];

                Session::put(['google_user' => $data_array]);

                $redirectUrl = (!empty($slug) && !empty($slug2)) ? "/$slug/$slug2/register" : '/register';

                return redirect()->to($redirectUrl);

            }

//            DailyTip::updateUserDailyTip();

            ActionPlan::storeUserActionPlan();

            User::updateUserIsFeedback();

            $user = Helpers::getWebUser();

            Helpers::createCustomerAndSubscriptionOnStripe($user);

            return redirect()->route('client_dashboard');

        } catch (\Exception $e) {

            return redirect()->to('/login')->with('error', $e->getMessage());
        }
    }

}
