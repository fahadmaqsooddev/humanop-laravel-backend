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

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
//        try {

            $user = Socialite::driver('google')->user();

            $finduser = User::where('google_id', $user->id)->orWhere('email', $user->email)->first();

            if($finduser){

                Auth::login($finduser);

            }else{
//                $newUser = User::create([
//                    'email' => $user->email,
//                    'first_name' => $user->user['given_name'] ?? "",
//                    'last_name' => $user->user['family_name'] ?? "",
//                    'google_id'=> $user->id,
//                    'password' => $user->id,
//                    'is_admin' => 2,
//                    'password_set' => 2,
//                ]);
//
//                Auth::login($newUser);

                $name = explode(' ', $user->name);

                $data_array = [
                    'google_id' => $user->id,
                    'first_name' => $name[0] ?? "",
                    'last_name' => $name[1] ?? "",
                    'email' => $user->email,
                ];

                Session::put(['google_user' => $data_array]);

                return redirect()->to('/register');

            }

            DailyTip::updateUserDailyTip();

            ActionPlan::storeUserActionPlan();

            User::updateUserIsFeedback();

            $user = Helpers::getWebUser();

            Helpers::createCustomerAndSubscriptionOnStripe($user);

            return redirect()->route('client_dashboard');

//        } catch (\Exception $e) {
//
//            return redirect()->to('/login')->with('error', $e->getMessage());
//        }
    }

}
