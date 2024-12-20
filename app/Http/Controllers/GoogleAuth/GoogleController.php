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

            }
        }

        if (!empty($slug) && !empty($slug2)) {

            Session::put('practitioner', $slug . ' ' . $slug2);

        }

        return Socialite::driver('google')->redirect();

    }
    public function handleGoogleCallback()
    {
        try {

            $practitionerSession = Session::get('practitioner');

            $googleUser = Socialite::driver('google')->user();

            $checkDeletedUser = User::checkDeleteEmail($googleUser['email']);

            if (!empty($checkDeletedUser))
            {
                $invite = UserInvite::getInviteLinkUsingEmail($googleUser['email']);

                session()->flash('error', 'Your account associated with this email has been frozen. Please contact our technical support team for assistance.');

                $url = last(\request()->segments());

                if ($url == false && $url == 'login')
                {
                    return redirect()->route('login');
                }
                else
                {
                    return redirect()->to('register?link=' . $invite['link']);
                }
            }

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
                $finduser = User::where(function ($query) use ($googleUser) {
                    $query->where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email);
                })
                    ->whereNotNull('email_verified_at')
                    ->first();
            }

            if ($finduser) {
                Auth::login($finduser);
            }
            else {

                $invite_link = Session::get('inviteLink');

                if (!empty($invite_link))
                {

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
                else{

                    return redirect()->back()->with('error', 'Invite link is missing. Please provide a valid link.');

                }

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

                return redirect()->route('client_dashboard');
            }

        } catch (\Exception $e) {

            return redirect()->to('/login')->with('error', $e->getMessage());
        }
    }


}
