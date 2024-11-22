<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\Email\Email;
use App\Models\Email\EmailTemplate;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;

class ChangePasswordController extends Controller
{
    public function changePassword(Request $request)
    {

        $request->validate([
//            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ],[
            'email.required' => 'Your reset link has been expired'
        ]);

        $user = User::where('email', $request['email'])->first();

        $user->password = $request['password'];

        $user->reset_password = 1;

        $user->save();

//        $status = Password::reset(
//            $request->only('email', 'password', 'password_confirmation'),
//            function ($user, $password) {
//                $user->forceFill([
//                    'password' => $password
//                ]);
//
//                $user->save();
//
////                event(new PasswordReset($user));
//            }
//        );



        Auth::logoutOtherDevices($user->password);
//        Auth::guard('api')->logoutOtherDevices($user->password);

        Session::flash('resetPasswordEmail');

        session()->flash('success', "Your password has been reset");

        return redirect()->route('login');

//        return $status === Password::PASSWORD_RESET
//            ? redirect('/login')->with('success', __($status))
//            : back()->withErrors(['email' => [__($status)]]);
    }

    public function create()
    {
        return view('session/reset-password/sendEmail');
    }

    public function sendEmail(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);

            $checkUserEmail = User::where('email', $request['email'])->first();

            if (!empty($checkUserEmail)) {

                $baseUrl = url('/reset-password');

                $data = [
                    '{$userName}' => $checkUserEmail['first_name'] .' ' . $checkUserEmail['last_name'],
                    '{$link}' =>  $baseUrl,
                ];

                $email_template = EmailTemplate::getTemplate($data, 'reset-password');

                Email::sendEmailVerification(['content' => $email_template], $checkUserEmail['email'],'emails.Email_Template', 'Reset Password');

                Session::put('resetPasswordEmail', $checkUserEmail['email']);

                session()->flash('success', "We have emailed your password reset link!");

                return redirect()->route('forgot_password');

            } else {

                session()->flash('error', "Email doesn't exists");
                return redirect()->route('forgot_password');

            }

//            $status = Password::sendResetLink(
//                $request->only('email')
//            );
//
//            return $status === Password::RESET_LINK_SENT
//                ? back()->with(['success' => __($status)])
//                : back()->withErrors(['email' => __($status)]);

        } catch (\Exception $exception) {

            return redirect()->back()->withInput()->withErrors(['server_error' => Helpers::serverErrorResponse($exception->getMessage())]);

        }

    }

    public
    function resetPass(Request $request)
    {

        // Retrieve the email parameter from the query string
        $email = $request->query('email');

        // Return the view with the token and email
        return view('session/reset-password/resetPassword', [
//            'token' => $token,
            'email' => $email,
        ]);
    }

    public
    function checkEmail($id = null)
    {
        $user = User::getSingleUser($id);

        if ($user) {

            User::emailVerified($user['id']);

            Auth::login($user);

//            DailyTip::updateUserDailyTip();

//            ActionPlan::storeUserActionPlan();

            return redirect()->route('client_dashboard');
        } else {
            return redirect()->to('/register');
        }
    }

    public
    function loginUserToDashboard($id = null)
    {
        $user = User::getSingleUser($id);

        if ($user) {

            Auth::login($user);

//            DailyTip::updateUserDailyTip();

            ActionPlan::storeUserActionPlan();

            return redirect()->route('client_dashboard');
        } else {
            return redirect()->to('/register');
        }
    }


    public
    function checkEmailFromApp($id = null)
    {
        $user = User::getSingleUser($id);

        if ($user) {

            User::emailVerified($user['id']);

            Auth::login($user);

            $user = User::userLoggedInData();

//            DailyTip::updateUserDailyTip();

            ActionPlan::storeUserActionPlan();

            return redirect()->route('email_verified');

        } else {
            return redirect()->to('/register');
        }
    }
}
