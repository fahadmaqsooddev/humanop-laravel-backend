<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Http\Requests\Client\Register\ResetPasswordRequest;
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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;

class ChangePasswordController extends Controller
{

    protected $user = null;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function changePassword(ResetPasswordRequest $request)
    {

        $dataArray = $request->only($this->user->getFillable());

        $token = $request['token'];

        $user = User::where('reset_password_token', $token)->first();

        if (!empty($token) && !empty($user)) {

            $user->password = $dataArray['password'];

            $user->reset_password = 1;

            $user->save();

            Auth::logoutOtherDevices($user->password);

            Session::flash('resetPasswordEmail');

            session()->flash('success', "Your password has been reset");

            return redirect()->route('login');
        } else {

            session()->flash('success', "Reset password link has been expired");

            return redirect()->back();
        }


    }

    public function create()
    {
        return view('session/reset-password/sendEmail');
    }

    public function resetPass(Request $request)
    {

        $token = $request->query('token');

        return view('session/reset-password/resetPassword', [
            'token' => $token,
        ]);
    }

    public function checkEmailVerification(Request $request)
    {
        $token = $request->query('token');

        $user = User::where('email_verify_token', $token)->first();

        if ($user) {

            if (empty($user['email_verified_at']))
            {
                User::emailVerified($user['id']);

                return redirect()->route('email_verified');
            }
            else
            {

                session()->flash('success', "You are already verified.");

                return redirect()->to('/login');

            }

        } else {

            session()->flash('success', "Email Verification link has been expired");

            return redirect()->to('/login');
        }
    }

    public function checkEmailFromApp($id = null)
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
