<?php

namespace App\Http\Controllers\GoogleAuth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Models\User;

class GoogleController extends Controller
{

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {

            $user = Socialite::driver('google')->user();

            dd($user);
            
            $finduser = User::where('google_id', $user->id)->orWhere('email', $user->email)->first();

            if($finduser){

                Auth::login($finduser);

            }else{
                $newUser = User::create([
                    'email' => $user->email,
                    'first_name' => $user->user['given_name'],
                    'last_name' => $user->user['family_name'],
                    'google_id'=> $user->id,
                    'password' => $user->id,
                    'is_admin' => 2,
                ]);

                Auth::login($newUser);

            }

            return redirect()->route('client_dashboard');

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

}
