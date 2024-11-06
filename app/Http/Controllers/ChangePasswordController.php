<?php

namespace App\Http\Controllers;

use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class ChangePasswordController extends Controller
{
    public function changePassword(Request $request)
    {

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => $password
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect('/login')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function create()
    {
        return view('session/reset-password/sendEmail');
    }

    public function sendEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['success' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetPass(Request $request, $token)
    {
        // Retrieve the email parameter from the query string
        $email = $request->query('email');

        // Return the view with the token and email
        return view('session/reset-password/resetPassword', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function checkEmail($id = null)
    {
        $user = User::getSingleUser($id);

        if ($user)
        {

            User::emailVerified($user['id']);

            Auth::login($user);

            DailyTip::updateUserDailyTip();

//            ActionPlan::storeUserActionPlan();

            return redirect()->route('client_dashboard');
        } else
        {
            return redirect()->to('/register');
        }
    }

    public function loginUserToDashboard($id = null)
    {
        $user = User::getSingleUser($id);

        if ($user)
        {

            Auth::login($user);

            DailyTip::updateUserDailyTip();

            ActionPlan::storeUserActionPlan();

            return redirect()->route('client_dashboard');
        } else
        {
            return redirect()->to('/register');
        }
    }





    public function checkEmailFromApp($id = null)
    {
        $user = User::getSingleUser($id);

        if ($user)
        {

            User::emailVerified($user['id']);

            Auth::login($user);

            $user = User::userLoggedInData();

            DailyTip::updateUserDailyTip();

            ActionPlan::storeUserActionPlan();

            return redirect()->route('email_verified');

        } else
        {
            return redirect()->to('/register');
        }
    }
}
