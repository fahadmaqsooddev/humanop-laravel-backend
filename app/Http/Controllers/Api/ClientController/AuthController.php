<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\CheckCandidate;
use App\Http\Requests\Api\Auth\CheckInviteLinkRequest;
use App\Http\Requests\Api\Auth\EmailVerifiedRequest;
use App\Http\Requests\Api\Client\ForgotPasswordRequest;
use App\Http\Requests\Api\Client\LoginRequest;
use App\Http\Requests\Api\Client\SendPhoneOtpRequest;
use App\Http\Requests\RegisterFirstStepRequest;
use App\Http\Requests\RegisterLastStepRequest;
use App\Models\B2B\B2BBusinessCandidates;
use App\Models\Email\Email;
use App\Models\Email\EmailTemplate;
use App\Models\Notification\PushNotification;
use App\Models\User;
use App\Models\UserInvite\UserInvite;
use Carbon\Carbon;
use Dompdf\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{

    protected $auth;

    public function __construct()
    {
        $this->middleware('auth:api')->except(['SendInvite', 'loginClient', 'forgotPassword', 'socialLogin', 'appVersion', 'resendEmailVerification', 'registerFirstStep', 'checkEmailVerification', 'registerLastStep', 'checkInviteLink', 'EmailVerified', 'sendPhoneOtp', 'checkUserDetail']);

        $this->auth = Auth::guard('api');
    }

    public function checkUserDetail(CheckCandidate $request)
    {
        try {
            $dataResult = $request->only(['token', 'company_name','prefer']);

            $invite = UserInvite::where('link', $dataResult['token'])->first();

            if (!empty($invite)) {

                $data = User::checkEmail($invite['email']);

                if (!empty($data)) {
                    $url = config('client_url.client_dashboard_url') . '/login?company_name=' . $dataResult['company_name'] . '&prefer=' . $dataResult['prefer'];
                    return Helpers::successResponse('An account with this email already exists. Please log in to continue.', [
                        'url' => $url,
                        'company_name' => $dataResult['company_name'],
                        'excisting_candidate' => true,


                    ]);
                } else {
                    $url = config('client_url.client_dashboard_url') . '/register?link=' . $dataResult['token'] . '&company_name=' . $dataResult['company_name'] . '&prefer=' . $dataResult['prefer'];
                    return Helpers::successResponse('Candidate Does not have an Account', [
                        'url' => $url,
                        'company_name' => $dataResult['company_name'],
                        'excisting_candidate' => false,
                    ]);
                }
            } else {
                return Helpers::validationResponse('In Valid token');
            }

        } catch (\Exception $exception) {


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

                    if ($request['b2b_invite'] == 1) {

                        $user = $user->createFirstStep($dataArray, $request['google_id'], $request['apple_id'], true);

                    } else {

                        $user = $user->createFirstStep($dataArray, $request['google_id'], $request['apple_id'], false);

                    }

                    if (!empty($request['company_name'])) {

                        $data = User::getSingleUserFromCompanyName($request['company_name']);

//                        if ($authorizedUser['role'] == Admin::B2B_INVITE_ROLE) {
//
//                            B2BBusinessCandidates::registerCandidate($data['id'], $user['id'], Admin::IS_CANDIDATE, Admin::NOT_SHARED_DATA);
//
//                        } else if ($authorizedUser['role'] == Admin::B2B_MEMBER_INVITE_ROLE) {
//
//                            B2BBusinessCandidates::registerCandidate($data['id'], $user['id'], Admin::IS_TEAM_MEMBER, Admin::NOT_SHARED_DATA);
//
//                        }
//
//                        B2BBusinessCandidates::registerCandidate($data['id'], $user['id'], Admin::IS_CANDIDATE, Admin::NOT_SHARED_DATA);

                        B2BBusinessCandidates::registerCandidate($data['id'], $user['id'], $request['prefer'], Admin::NOT_SHARED_DATA);
                    }

                    if (!empty($request['register_from_app'])) {

                        $url = config('client_url.client_dashboard_url') . '/email-verified?token=' . $user['email_verify_token'];

                    } else {

                        $url = config('client_url.client_dashboard_url') . '/email-verified?token=' . $user['email_verify_token'] . '&app=azklmwosdf';

                    }

                    $user->setAppends([]);

                    if (empty($request['google_id']) && empty($request['apple_id'])) {

                        $emailData = $this->prepareEmailData($user, $url);

                        $this->sendEmailVerification($emailData, $user['email'], 'Verify Your Email Address');
                    }

                    Helpers::createCustomerAndSubscriptionOnStripe($user);

                    Helpers::createClientsOnOneSignal($user['id']);

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

                        if (!empty($request['register_from_app'])) {
                            $url = config('client_url.client_dashboard_url') . '/email-verified?token=' . $checkUser['email_verify_token'];
                        } else {
                            $url = config('client_url.client_dashboard_url') . '/email-verified?token=' . $checkUser['email_verify_token'] . '&app=azklmwosdf';
                        }

                        $emailData = $this->prepareEmailData($checkUser, $url);

                        $this->sendEmailVerification($emailData, $checkUser['email'], 'Verify Your Email Address');

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

                            if (!empty($request['company_name'])) {

                                $data = User::getSingleUserFromCompanyName($request['company_name']);

//                                if ($authorizedUser['role'] == Admin::B2B_INVITE_ROLE) {
//
//                                    B2BBusinessCandidates::registerCandidate($data['id'], $user['id'], Admin::IS_CANDIDATE, Admin::NOT_SHARED_DATA);
//
//                                } else if ($authorizedUser['role'] == Admin::B2B_MEMBER_INVITE_ROLE) {
//
//                                    B2BBusinessCandidates::registerCandidate($data['id'], $user['id'], Admin::IS_TEAM_MEMBER, Admin::NOT_SHARED_DATA);
//
//                                }

                                B2BBusinessCandidates::registerCandidate($data['id'], $user['id'], $request['prefer'], Admin::NOT_SHARED_DATA);

                            }

                            DB::commit();

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

    public function registerLastStep(RegisterLastStepRequest $request)
    {
        try {

            $getUser = User::getSingleUser($request['user_id']);

            if ($getUser) {

                $dataArray = $request->only((new User())->getFillable());

                $dataArray['step'] = 3;

                $dataArray['gender'] = $request->input('gender') === 'male' ? 0 : 1;

                tap($getUser->update($dataArray));

                PushNotification::createNotification($request['user_id']);

                $getUser['two_way_auth'] = ($getUser['two_way_auth'] === Admin::TWO_WAY_AUTH_ACTIVE ? true : false);

                $getUser['app_intro_check'] = ($getUser['app_intro_check'] === Admin::INTRO_CHECK_UN_READ ? true : false);

                if ($getUser['is_admin'] == Admin::IS_B2B) {

                    $getUser->setAppends([]);

                    $data = [
                        'user' => $getUser,
                        'b2b_create_Account' => true,
                    ];

                    return Helpers::successResponse('Complete Your maestro Signup Process', $data);

                } else {

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

            }

            return Helpers::validationResponse('User not found');

        } catch (\Exception $exception) {
            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function loginClient(LoginRequest $request)
    {

        try {

            DB::beginTransaction();

            $credentials = $request->only(['email', 'password']);

            $checkDeletedUser = User::checkDeleteEmail($credentials['email']);

            if (!empty($checkDeletedUser)) {

                return Helpers::validationResponse('Your account associated with this email has been frozen. Please contact our technical support team for assistance.');
            }

            $checkUser = User::checkEmail($credentials['email']);

            if (empty($checkUser)) {

                return Helpers::validationResponse("These credentials do not match our records.");
            } else if ($checkUser && $checkUser['email_verified_at'] == null) {

                 $userInvite = UserInvite::getSingleInvite($checkUser['email']);

                $userData = [
                    'user_id' => $checkUser['id'],
                    'user_name' => $checkUser['first_name'] . ' ' . $checkUser['last_name'],
                    'email' => $checkUser['email'],
                    'registration_step' => $checkUser['step'],
                     'user_invite' => $userInvite['link']

                ];

                return Helpers::successResponse('Your email is not verified. Kindly verify your email to continue.', $userData);
            } else if ($checkUser && $checkUser['step'] != 3) {

                 $userInvite = UserInvite::getSingleInvite($checkUser['email']);

                $userData = [
                    'user_id' => $checkUser['id'],
                    'user_name' => $checkUser['first_name'] . ' ' . $checkUser['last_name'],
                    'email' => $checkUser['email'],
                    'registration_step' => $checkUser['step'],
                     'user_invite' => $userInvite['link']

                ];

                return Helpers::successResponse('Please complete all required steps in the signup process to log in.', $userData);
            } else {

                $remember_me = $request['remember'] == 'true' ? true : false;

                if ($remember_me == true) {

                    $token = $this->auth->attempt($credentials, $remember_me);

                    $getUser = User::getSingleUser($checkUser['id']);

                    $getUser->update(['last_login' => Carbon::now()]);
                } else {

                    $token = $this->auth->attempt($credentials);

                    $getUser = User::getSingleUser($checkUser['id']);

                    $getUser->update(['last_login' => Carbon::now()]);
                }

                if ($token) {

                    $user = Helpers::getUser();

                    if ($request['company_name']) {

                        $data = User::getSingleUserFromCompanyName($request['company_name']);

                        if (!empty($data)) {
                            B2BBusinessCandidates::registerCandidate($data['id'], $user['id'], $request['prefer'], Admin::NOT_SHARED_DATA);
                        }

                    }

                    Helpers::createCustomerAndSubscriptionOnStripe($user);

                    Helpers::createClientsOnOneSignal($user['id']);

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

                    DB::commit();

                    return Helpers::successResponse('User loggedIn successfully', $data);
                } else {

                    DB::rollBack();

                    return Helpers::unauthResponse('Wrong Password');
                }
            }
        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function socialLogin(Request $request)
    {

        try {

            $checkDeletedUser = User::checkDeleteEmail($request->input('email'));

            if (!empty($checkDeletedUser)) {

                return Helpers::validationResponse('Your account associated with this email has been frozen. Please contact our technical support team for assistance.');
            }

            $user = User::checkUserFromEmailOrSocialId($request);

            if ($user) {

                if ($user['email_verified_at'] == null) {

                    $userData = [
                        'user_id' => $user['id'],
                        'registration_step' => $user['step']
                    ];

                    return Helpers::successResponse('Your email is not verified. Kindly verify your email to continue.', $userData);
                }

                if ($user['step'] != 3) {
                    $userData = [
                        'user_id' => $user['id'],
                        'registration_step' => $user['step']
                    ];

                    return Helpers::successResponse('Please complete all required steps in the signup process to log in.', $userData);
                }


                if (!empty($request['company_name'])) {

                    $data = User::getSingleUserFromCompanyName($request['company_name']);

                    if (!empty($data)) {
                        B2BBusinessCandidates::registerCandidate($data['id'], $user['id'], $request['prefer'], Admin::NOT_SHARED_DATA);
                    }

                }

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

            return Helpers::validationResponse('Email does not exists. Please signup first.');

        } catch (Exception $exception) {

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

                    //                    $link = env('CLIENT_DASHBOARD_URL') . '/register?link=' . $getInvite['link'];
                    $link = config('client_url.client_dashboard_url') . '/register?link=' . $getInvite['link'];

                    return response()->json(['link' => $link]);
                } else {

                    $createlink = UserInvite::sendInvite($validatedData['email']);

                    //                    $link = env('CLIENT_DASHBOARD_URL') . '/register?link=' . $createlink['link'];
                    $link = config('client_url.client_dashboard_url') . '/register?link=' . $createlink['link'];

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

                //                $url = env('CLIENT_DASHBOARD_URL') . '/reset-password?token=' . $token['reset_password_token'];
                $url = config('client_url.client_dashboard_url') . '/reset-password?token=' . $token['reset_password_token'];

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

            $signup = $request->input('signup');

            $user = User::getSingleUser($request->input('user_id'));

            $updateProfile = User::generateEmailVerificationToken($user['email']);

            if (!empty($user['register_from_app'])) {

                $baseUrl = config('client_url.client_dashboard_url') . '/email-verified?token=' . $updateProfile['email_verify_token'];

            } else {

                $baseUrl = config('client_url.client_dashboard_url') . '/email-verified?token=' . $updateProfile['email_verify_token'] . '&app=azklmwosdf';

            }


            $logoUrl = URL::asset('assets/logos/HumanOp Logo.png');
            $privacyUrl = url('/privacy-policy');
            $serviceUrl = url('/term-of-service');

            $data = [
                '{$userName}' => $updateProfile['first_name'] . ' ' . $updateProfile['last_name'],
                '{$link}' => $baseUrl,
                '{$logo}' => $logoUrl,
                '{$service}' => $serviceUrl,
                '{$privacy}' => $privacyUrl,
            ];

            $email_template = EmailTemplate::getTemplate($data, 'Verify Your Email Address');

            Email::sendEmailVerification(['content' => $email_template], $updateProfile['email'], 'emails.Email_Template', 'Verify Your Email Address');

            return Helpers::successResponse('Resend email sent successfully!');
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

            $userInvite = UserInvite::getSingleInvite($user['email']);

            $data = [
                'user' => $user,
                'user_invite' => $userInvite['link'],
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

    public function checkInviteLink(CheckInviteLinkRequest $request)
    {
        try {

            $inviteLink = UserInvite::getInviteLink($request['invite_link']);

            if (!empty($inviteLink)) {

                $getUser = User::checkEmail($inviteLink['email']);

                $data = [
                    'user_id' => $getUser ? $getUser['id'] : null,
                    'user_email' => $inviteLink['email'],
                    'user_name' => $getUser ? $getUser['first_name'] . ' ' . $getUser['last_name'] : null,
                ];

                return Helpers::successResponse('User Invite link email', $data);

            } else {

                return Helpers::validationResponse('You are not recognized. Please check the invite link or contact support.');

            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }
}
