<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Auth\SocialLoginRequest;
use App\Http\Requests\Api\Client\ForgotPasswordRequest;
use App\Http\Requests\Api\Client\LoginRequest;
use App\Http\Requests\Api\Client\RegisterRequest;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\Email\Email;
use App\Models\Email\EmailTemplate;
use App\Models\IntentionPlan\IntentionPlan;
use App\Models\User;
use Carbon\Carbon;
use Dompdf\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    protected $auth;

    public function __construct()
    {
        $this->middleware('auth:api')->except(['loginClient', 'registerClient', 'forgotPassword', 'socialLogin','appVersion','resendEmailVerification']);

        $this->auth = Auth::guard('api');
    }

    public function loginClient(LoginRequest $request)
    {

        try {

            $credentials = $request->only(['email', 'password']);

            $credentials['status'] = 1;

            $credentials['is_admin'] = Admin::IS_CUSTOMER;

            $checkUser = User::where('email', $credentials['email'])->whereNotNull('email_verified_at')->exists();

            if ($checkUser == true)
            {
                $token = $this->auth->attempt($credentials);
                if ($token) {

                    $user_data = User::user(Helpers::getUser()->id);
//                User::updateUserIsFeedback();

                    DailyTip::updateUserDailyTip();

//                    ActionPlan::storeUserActionPlan();

                    $user = Helpers::getUser();

                    Helpers::createCustomerAndSubscriptionOnStripe($user);

                    $data = [
                        'user' => $user_data,
                        'authorization' => [
                            'token' => $token,
                            'type' => 'bearer',
                        ]
                    ];

                    return Helpers::successResponse('User loggedIn successfully', $data);

                }
                else {

                    return Helpers::unauthResponse('Wrong Password');
                }
            }else
            {
                return Helpers::validationResponse('Your email is not verified. Kindly verify your email to continue.');

            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function registerClient(RegisterRequest $request)
    {

        DB::beginTransaction();

        try {

            $user = new User();

            $dataArray = $request->only($user->getFillable());

            $user = User::createClient($dataArray);

            if ($request->has('referred_by_code') && !empty($request['referred_by_code'])) {
                $referredBy = User::where('referral_code', $request['referred_by_code'])->first();
                if ($referredBy) {
                    $user->update(['referred_by' => $referredBy->id]);
                }
            }

            if ($request->has('ninety_day_intention') && !empty($request['ninety_day_intention'])) {

                IntentionPlan::createIntentionPlan($user['id'], $request['ninety_day_intention']);
            }

            if (empty($request['google_id']))
            {
                $baseUrl = url('/check-email', $user['id']);

                $userData = [
                    '{$userName}' => $user['first_name'] . ' ' . $user['last_name'],
                    '{$link}' => $baseUrl,
                ];


                $email_template = EmailTemplate::getTemplate($userData, 'email-verification');

                Email::sendEmailVerification(['content' => $email_template], $user['email'], 'emails.Email_Template', 'Email Verification');

                Helpers::createCustomerAndSubscriptionOnStripe($user);

                DB::commit();

                $data = [
                    'authorization' => [
                        'userId' => $user['id'],
                        'status' => true,
                        'type' => 'bearer',
                    ]
                ];

            }
            else
            {
                User::emailVerified($user['id']);

                $token = $this->auth->login($user);

                $user = User::userLoggedInData();

                Helpers::createCustomerAndSubscriptionOnStripe($user);

                $user['gender'] = ($user['gender'] === 0 || $user['gender'] === '0' ? "male" : "female");

                DailyTip::updateUserDailyTip();

//                ActionPlan::storeUserActionPlan();

                DB::commit();

                $data = [
                    'user' => $user,
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ];

            }

            return Helpers::successResponse('User register successfully', $data);

        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function logoutClient()
    {

        try {

            $this->auth->logout();

            return Helpers::successResponse('User logged out successfully');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {

        try {

            Password::sendResetLink($request->only('email'));

            return Helpers::successResponse('Password reset email successfully sent');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function socialLogin(SocialLoginRequest $request)
    {

        try {

            $user = User::checkUserFromEmailOrSocialId($request);

            if ($user) {

                $token = $this->auth->login($user);

                DailyTip::updateUserDailyTip();

//                ActionPlan::storeUserActionPlan();

                $data = [
                    'user' => $user,
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ];

                return Helpers::successResponse('User loggedIn successfully', $data);

            }

            return Helpers::validationResponse('Email does not exists');

        } catch (Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function appVersion()
    {

        try {

            return Helpers::successResponse('version', 'Version 1.0.0');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function resendEmailVerification(Request $request)
    {

        try {

            $user = User::getSingleUser($request->input('user_id'));

            $baseUrl = url('/check-email', $user['id']);

            $data = [
                '{$userName}' => $user['first_name'] .' ' . $user['last_name'],
                '{$link}' =>  $baseUrl,
            ];

            $email_template = EmailTemplate::getTemplate($data, 'email-verification');

            Email::sendEmailVerification(['content' => $email_template], $user['email'],'emails.Email_Template', 'Email Verification');


            return Helpers::successResponse('Resend email sent successfully!');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }
}
