<?php

namespace App\Http\Controllers\GoogleAuth;

use App\Helpers\Practitioner\PractitionerHelpers;
use App\Http\Controllers\Controller;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\UserInvite\UserInvite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\Helpers;

class GoogleController extends Controller
{

    public function redirectToGoogle(Request $request, $slug = null, $slug2 = null)
    {
        $inviteLink = $request->query('link');

        if (!empty($inviteLink))
        {
            $invite = UserInvite::getInviteLink($inviteLink);

            if (!empty($invite))
            {
                Session::put('inviteLink', $invite['link']);

                if (!empty($slug) && !empty($slug2)) {

                    Session::put('practitioner', $slug . ' ' . $slug2);

                }

                return Socialite::driver('google')->redirect();
            }
            else
            {
                return redirect()->back()->with('error', 'You are not recognized. Please check the invite link or contact support.');

            }
        }
        else
        {
            return redirect()->back()->with('error', 'Invite link is missing. Please provide a valid link.');
        }

    }
    public function handleGoogleCallback()
    {
        try {

            $practitionerSession = Session::get('practitioner');

            $googleUser = Socialite::driver('google')->user();

            $finduser = null;

            if (!empty($practitionerSession)) {

                $parts = explode(' ', $practitionerSession);

                if (count($parts) >= 2) {
                    $firstName = $parts[0];
                    $lastName = $parts[1];

                    $practitioner = User::where('first_name', $firstName)
                        ->where('last_name', $lastName)
                        ->first('id');

                    if ($practitioner) {
                        $finduser = User::where('practitioner_id', $practitioner->id)
                            ->orWhere('google_id', $googleUser->id)
                            ->orWhere('email', $googleUser->email)
                            ->first();
                    }
                }
            }
            else {
                $finduser = User::where('google_id', $googleUser->id)
                    ->orWhere('email', $googleUser->email)
                    ->first();
            }

            if ($finduser) {
                Auth::login($finduser);
            }
            else {

                $nameParts = explode(' ', $googleUser->name);

                $dataArray = [
                    'google_id' => $googleUser->id,
                    'first_name' => $nameParts[0] ?? "",
                    'last_name' => $nameParts[1] ?? "",
                    'email' => $googleUser->email,
                ];

                Session::put('google_user', $dataArray);

                if (!empty($practitionerSession) && count($parts) >= 2) {
                    $redirectUrl = "/$firstName/$lastName/register";
                } else {


                    $redirectUrl = '/register?link='. $invite_link;
                }


                return redirect()->to($redirectUrl);
            }

            User::updateUserIsFeedback();

            $authenticatedUser = Helpers::getWebUser();

            Helpers::createCustomerAndSubscriptionOnStripe($authenticatedUser);

//            Session::forget('practitioner');

            if (!empty($practitionerSession))
            {
                $parts = explode(' ', $practitionerSession);

                return redirect()->to('/'. $parts[0] . '/' . $parts[1]. '/dashboard');

            }
            else
            {
                dd(1);

                return redirect()->route('client_dashboard');
            }


//            $invite_link = Session::get('inviteLink');
//
//            if (!empty($invite_link))
//            {
//
//            }



        } catch (\Exception $e) {

            return redirect()->to('/login')->with('error', $e->getMessage());
        }
    }


}
