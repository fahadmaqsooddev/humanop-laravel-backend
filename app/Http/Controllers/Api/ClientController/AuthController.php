<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\CheckInviteLinkRequest;
use App\Http\Requests\Api\Auth\EmailVerifiedRequest;
use App\Http\Requests\Api\Client\Auth\SocialLoginRequest;
use App\Http\Requests\Api\Client\ForgotPasswordRequest;
use App\Http\Requests\Api\Client\LoginRequest;
use App\Http\Requests\Api\Client\RegisterRequest;
use App\Http\Requests\Api\Client\SendPhoneOtpRequest;
use App\Http\Requests\RegisterFirstStepRequest;
use App\Http\Requests\RegisterLastStepRequest;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\Email\Email;
use App\Models\Email\EmailTemplate;
use App\Models\IntentionPlan\IntentionPlan;
use App\Models\User;
use App\Models\UserInvite\UserInvite;

use Carbon\Carbon;
use Dompdf\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    protected $auth;

    public function __construct()
    {
        $this->middleware('auth:api')->except(['SendInvite', 'loginClient', 'registerClient', 'forgotPassword', 'socialLogin', 'appVersion', 'resendEmailVerification', 'registerFirstStep', 'checkEmailVerification', 'registerLastStep', 'checkInviteLink', 'EmailVerified', 'sendPhoneOtp']);

        $this->auth = Auth::guard('api');
    }

    public function loginClient(LoginRequest $request)
    {

        try {

            $credentials = $request->only(['email', 'password']);

            $credentials['status'] = 1;

            $credentials['is_admin'] = Admin::IS_CUSTOMER;

            $checkDeletedUser = User::checkDeleteEmail($credentials['email']);

            if (!empty($checkDeletedUser)) {

                return Helpers::validationResponse('Your account associated with this email has been frozen. Please contact our technical support team for assistance.');
            }

            $checkUser = User::checkEmail($credentials['email']);

            if (empty($checkUser)) {

                return Helpers::validationResponse("These credentials do not match our records.");

            } else if ($checkUser && $checkUser['email_verified_at'] == null) {

                return Helpers::validationResponse('Your email is not verified. Kindly verify your email to continue.');

            } else {

                $remember_me = $request['remember'] == 'true' ? true : false;

                if ($remember_me == true) {

                    $token = $this->auth->attempt($credentials, $remember_me);

                } else {

                    $token = $this->auth->attempt($credentials);

                }

                if ($token) {

                    $data = User::where('email', $request['email'])->first();

                    if ($data['step'] != 3) {

                        $userData = [
                            'user_id' => $data['id'],
                            'registration_step' => $data['step']
                        ];

                        return Helpers::successResponse('Please complete all required steps in the signup process to log in.', $userData);

                    } else {

                        $user_data = User::user(Helpers::getUser()->id);

                        $user = Helpers::getUser();

                        Helpers::createCustomerAndSubscriptionOnStripe($user);

                        $updateUser = User::updateUserIsFeedback();

                        $updateUser['two_way_auth'] = ($updateUser['two_way_auth'] === Admin::TWO_WAY_AUTH_ACTIVE ? true : false);
                        $updateUser['app_intro_check'] = ($updateUser['app_intro_check'] === Admin::INTRO_CHECK_UN_READ ? true : false);

                        $data = [
                            'user' => $updateUser,
                            'authorization' => [
                                'token' => $token,
                                'type' => 'bearer',
                            ]
                        ];
                        return Helpers::successResponse('User loggedIn successfully', $data);
                    }


                } else {

                    return Helpers::unauthResponse('Wrong Password');
                }
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

            $authorizedUser = UserInvite::getSingleInvite($dataArray['email']);

            if (!empty($authorizedUser)) {

                $checkDeleteAccount = $user->checkDeleteEmail($dataArray['email']);

                if (!empty($checkDeleteAccount)) {

                    return Helpers::validationResponse('Your account associated with this email has been frozen. Please contact our technical support team for assistance.');
                }

                $checkUser = User::checkEmail($dataArray['email']);

                if (empty($checkUser)) {
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

                    if (empty($request['google_id']) && empty($request['apple_id'])) {

//                        $baseUrl = url('/check-email?token='. $user['email_verify_token']);

//                        $baseUrl = "https://human-opi.vercel.app/email-validate?token=" . $user['email_verify_token'];

                        $baseUrl = env('CLIENT_DASHBOARD_URL') . '/email-validate?token=' . $user['email_verify_token'];

                        $logoUrl = URL::asset('assets/logos/HumanOp Logo.png');
                        $privacyUrl = url('/privacy-policy');
                        $serviceUrl = url('/term-of-service');

                        $userData = [
                            '{$userName}' => $user['first_name'] . ' ' . $user['last_name'],
                            '{$link}' => $baseUrl,
                            '{$logo}' => $logoUrl,
                            '{$service}' => $serviceUrl,
                            '{$privacy}' => $privacyUrl,
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

                    } else {
                        User::emailVerified($user['id']);

                        $token = $this->auth->login($user);

                        $user = User::userLoggedInData();

                        Helpers::createCustomerAndSubscriptionOnStripe($user);

                        $user['gender'] = ($user['gender'] === 0 || $user['gender'] === '0' ? "male" : "female");

                        DB::commit();

                        $user['two_way_auth'] = ($user['two_way_auth'] === Admin::TWO_WAY_AUTH_ACTIVE ? true : false);
                        $user['app_intro_check'] = ($user['app_intro_check'] === Admin::INTRO_CHECK_UN_READ ? true : false);

                        $data = [
                            'user' => $user,
                            'authorization' => [
                                'token' => $token,
                                'type' => 'bearer',
                            ]
                        ];

                    }

                    return Helpers::successResponse('User register successfully', $data);
                } else {
                    return Helpers::serverErrorResponse('Your email already exists');

                }
            } else {

                return Helpers::validationResponse('You are not recognized. Please check the invite link or contact support.');
            }


        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function registerFirstStep(RegisterFirstStepRequest $request)
    {
        DB::beginTransaction();

        try {

            $user = new User();

            $dataArray = $request->only($user->getFillable());

            $parts = explode(' ', $request->input('full_name'));

            $dataArray['first_name'] = $parts[0] ?? '';

            $dataArray['last_name'] = $parts[1] ?? '';

            $authorizedUser = UserInvite::getSingleInvite($dataArray['email']);

            if (!empty($authorizedUser)) {

                $checkDeleteAccount = $user->checkDeleteEmail($dataArray['email']);

                if (!empty($checkDeleteAccount)) {
                    return Helpers::validationResponse('Your account associated with this email has been frozen. Please contact our technical support team for assistance.');
                }

                $checkUser = $user->checkEmail($dataArray['email']);


                if (empty($checkUser)) {

                    $user = $user->createFirstStep($dataArray, $request['google_id'], $request['apple_id']);


                    if (!empty($request['register_from_app']))
                    {
                        $url = env('CLIENT_DASHBOARD_URL') . '/email-verified?token=' . $user['email_verify_token'];

                    }else{

                        $url = env('CLIENT_DASHBOARD_URL') . '/email-verified?token=' . $user['email_verify_token'] . '&app=0';

                    }

                    $user->setAppends([]);

                    if (empty($request['google_id']) && empty($request['apple_id'])) {

                        $emailData = $this->prepareEmailData($user, $url);

                        $this->sendEmailVerification($emailData, $user['email'], 'email-verification');

                    }

                    Helpers::createCustomerAndSubscriptionOnStripe($user);

                    DB::commit();

                    return Helpers::successResponse('User registered successfully', [
                        'authorization' => [
                            'user' => $user,
                            'status' => true,
                            'type' => 'bearer',
                        ],
                    ]);

                } else {

                    $checkEmailVerified = User::checkEmailVerified($checkUser['email']);

                    if (empty($checkEmailVerified)) {

                        $url = env('CLIENT_DASHBOARD_URL') . '/email-verified?token=' . $checkUser['email_verify_token'];

                        $emailData = $this->prepareEmailData($checkUser, $url);

                        $this->sendEmailVerification($emailData, $checkUser['email'], 'email-verification');

                        $checkUser->setAppends([]);

                        return Helpers::successResponse('Your email is not verified. Verification email sent.', [
                            'authorization' => [
                                'user' => $checkUser,
                                'status' => true,
                                'type' => 'bearer',
                            ],
                        ]);

                    } else {

                        $checkLastStep = User::checkLastStep($checkUser['email']);

                        if ($checkLastStep && $checkLastStep['step'] == 3) {

                            return Helpers::validationResponse('An account with this email already exists. Please log in to continue.');

                        } else {

                            $checkLastStep->setAppends([]);

                            return Helpers::successResponse('kindly complete your last step', [

                                'authorization' => [
                                    'user' => $checkLastStep,
                                    'status' => true,
                                    'type' => 'bearer',
                                ],
                            ]);
                        }


                    }

                }
            } else {

                return Helpers::validationResponse('You are not recognized. Please check the invite link or contact support.');
            }

        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }

    public function SendInvite(Request $request)
    {
        try {
            $email = $request->query('email');

            $inviteKey = config('inviteKey.key');

            $key = $request->query('key');

            $validatedData = \Validator::make(['email' => $email], [

                'email' => 'required|email',

            ])->validate();

            if ($key == $inviteKey) {
                $getInvite = UserInvite::where('email', $validatedData['email'])->first();

                if (!empty($getInvite)) {

//                    $link = url('/register?link=' . $getInvite['link']);

                    $link = env('CLIENT_DASHBOARD_URL') . '/register?link=' . $getInvite['link'];

                    return response()->json(['link' => $link]);

                } else {

                    $createlink = UserInvite::sendInvite($validatedData['email']);

                    $link = env('CLIENT_DASHBOARD_URL') . '/register?link=' . $createlink['link'];

                    return response()->json(['link' => $link]);

                }
            } else {
                return response()->json(['error' => 'key is not valid']);
            }


        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'msg' => $e->getMessage(),

            ]);

        }
    }

    public function sendPhoneOtp(SendPhoneOtpRequest $request)
    {
        try {

            $email = $request->input('email');

            $checkUserEmail = User::checkEmail($email);

            $otpNumber = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

            $emailData = $this->prepareEmailData($checkUserEmail, null, $otpNumber);

            $this->sendEmailVerification($emailData, $email, '2fa-verification-code');

            return Helpers::successResponse('Otp sent Successfully', ['otp' => $otpNumber]);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }

    private function prepareEmailData($user = null, $url = null, $codeNumber = null)
    {
        return [
            '{$userName}' => $user['first_name'] . ' ' . $user['last_name'],
            '{$link}' => $url,
            '{$code}' => $codeNumber,
            '{$logo}' => URL::asset('assets/logos/HumanOp Logo.png'),
            '{$service}' => url('/term-of-service'),
            '{$privacy}' => url('/privacy-policy'),
        ];
    }

    private function sendEmailVerification($emailData, $recipientEmail, $name)
    {
        $emailTemplate = EmailTemplate::getTemplate($emailData, $name);

        Email::sendEmailVerification(
            ['content' => $emailTemplate],
            $recipientEmail,
            'emails.Email_Template',
            $name
        );
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

            $request->validate(['email' => 'required|email']);


            $checkUserEmail = User::where('email', $request['email'])->first();


            if (!empty($checkUserEmail)) {

                $token = User::generateToken($checkUserEmail['email']);

//                $url = "https://human-opi.vercel.app/reset-password?token=" . $token['reset_password_token'];

                $url = env('CLIENT_DASHBOARD_URL') . '/reset-password?token=' . $token['reset_password_token'];

                $emailData = $this->prepareEmailData($checkUserEmail, $url);

                $this->sendEmailVerification($emailData, $checkUserEmail['email'], 'reset-password');

                return Helpers::successResponse('We have emailed your password reset link!');

            } else {

                return Helpers::validationResponse('Email does not exists');

            }


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

                $updateUser = User::updateUserIsFeedback();

                $updateUser['two_way_auth'] = ($updateUser['two_way_auth'] === Admin::TWO_WAY_AUTH_ACTIVE ? true : false);
                $updateUser['app_intro_check'] = ($updateUser['app_intro_check'] === Admin::INTRO_CHECK_UN_READ ? true : false);

                $data = [
                    'user' => $updateUser,
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

//            $baseUrl = "https://human-opi.vercel.app/email-validate?token=" . $user['email_verify_token'];

            $baseUrl = env('CLIENT_DASHBOARD_URL') . '/email-validate?token=' . $user['email_verify_token'];

            $logoUrl = URL::asset('assets/logos/HumanOp Logo.png');
            $privacyUrl = url('/privacy-policy');
            $serviceUrl = url('/term-of-service');

            $data = [
                '{$userName}' => $user['first_name'] . ' ' . $user['last_name'],
                '{$link}' => $baseUrl,
                '{$logo}' => $logoUrl,
                '{$service}' => $serviceUrl,
                '{$privacy}' => $privacyUrl,
            ];

            $email_template = EmailTemplate::getTemplate($data, 'email-verification');

            Email::sendEmailVerification(['content' => $email_template], $user['email'], 'emails.Email_Template', 'Email Verification');


            return Helpers::successResponse('Resend email sent successfully!');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function checkEmailVerification(Request $request)
    {
        try {


            $user = User::getSingleUser($request['user_id']);

            $user = User::checkEmailVerified($user['email']);

            if (!empty($user)) {

                $user->setAppends([]);

                return Helpers::successResponse('Your Email is verified', $user);

            } else {

                return Helpers::validationResponse('Your Email is not verified');

            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function EmailVerified(EmailVerifiedRequest $request)
    {
        try {
            $token = $request->query('token');

            $user = User::where('email_verify_token', $token)->first();

            if (!$user) {
                return Helpers::validationResponse('Email verification has expired.');
            }

            if (empty($user->email_verified_at)) {

                User::emailVerified($user->id);

                $user->refresh();
            }

            $authToken = $this->auth->login($user);

            $data = [
                'user' => $user,
                'authorization' => [
                    'token' => $authToken,
                    'type' => 'bearer',
                ],
            ];


            return Helpers::successResponse('Your Email is verified.', $data);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function registerLastStep(RegisterLastStepRequest $request)
    {
        try {

            $getUser = User::getSingleUser($request['user_id']);

            if ($getUser) {

                $dataArray = $request->only((new User())->getFillable());

                $dataArray['step'] = 3;

                $dataArray['gender'] = $request->input('gender') === 'male' ? 0 : 1;

                tap($getUser->update($dataArray));

                $getUser['two_way_auth'] = ($getUser['two_way_auth'] === Admin::TWO_WAY_AUTH_ACTIVE ? true : false);

                $getUser['app_intro_check'] = ($getUser['app_intro_check'] === Admin::INTRO_CHECK_UN_READ ? true : false);

                $token = $this->auth->login($getUser);

                $data = [
                    'user' => $getUser,
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ],
                ];

                return Helpers::successResponse('User logged in successfully', $data);
            }

            // If user not found

            return Helpers::errorResponse('User not found');

        } catch (\Exception $exception) {
            // Handle exceptions
            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function checkInviteLink(CheckInviteLinkRequest $request)
    {
        try {

            $inviteLink = UserInvite::getInviteLink($request['invite_link']);

            if (!empty($inviteLink)) {
                return Helpers::successResponse('User Invite link email', $inviteLink['email']);
            } else {
                return Helpers::validationResponse('You are not recognized. Please check the invite link or contact support.');
            }


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

}
