<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Enums\Admin\Admin;
use App\Helpers\HaiChat\HaiChatHelpers;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\CheckCandidate;
use App\Http\Requests\Api\Auth\CheckInviteLinkRequest;
use App\Http\Requests\Api\Auth\EmailVerifiedRequest;
use App\Http\Requests\Api\Client\ForgotPasswordRequest;
use App\Http\Requests\Api\Client\LoginRequest;
use App\Http\Requests\Api\Client\SendPhoneOtpRequest;
use App\Http\Requests\Client\Register\SmsCodeRequest;
use App\Http\Requests\Client\Register\SmsRequest;
use App\Http\Requests\RegisterFirstStepRequest;
use App\Http\Requests\RegisterLastStepRequest;
use App\Models\Admin\DailyTip\UserDailyTip;
use App\Models\Admin\RecentActivity\RecentActivity;
use App\Models\Assessment;
use App\Models\B2B\B2BBusinessCandidates;
use App\Models\B2B\UserCandidateInvite;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\Client\Point\Point;
use App\Models\Email\Email;
use App\Models\Email\EmailTemplate;
use App\Models\IntentionPlan\IntentionOption;
use App\Models\IntentionPlan\IntentionPlan;
use App\Models\Notification\PushNotification;
use App\Models\User;
use App\Models\UserInvite\UserInvite;
use App\Models\UserInvite\UserInviteLog;
use App\Services\AwsSnsServices\SnsServices;
use Carbon\Carbon;
use Dompdf\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    protected $auth;
    protected $sns;

    public function __construct(SnsServices $sns)
    {
        $this->middleware('auth:api')->except(['SendInvite', 'loginClient', 'forgotPassword', 'socialLogin', 'getUserInfoForHai', 'resendEmailVerification', 'registerFirstStep', 'checkEmailVerification', 'registerLastStep', 'checkInviteLink', 'EmailVerified', 'sendPhoneOtp', 'checkUserDetail', 'sendSmsCode', 'SmsCodeVerification', 'intentionOption']);

        $this->auth = Auth::guard('api');

        $this->sns = $sns;
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

                        $user = $user->createFirstStep($dataArray, $request['google_id'], $request['apple_id'], false, $request['ref']);

                    }

                    Point::addPoints(Admin::FREEMIUM_CREDITS, $user);

                    Point::addPoints(Admin::CORE_CREDITS, $user, 1);

                    HaiChatHelpers::syncUserRecordWithHAi($user);

                    if (!empty($request['company_name'])) {

                        $data = User::getSingleUserFromCompanyName($request['company_name']);

                        B2BBusinessCandidates::registerCandidate($data['id'], $user['id'], $request['prefer'], Admin::DECLINED_DATA);

                        $role = $request['prefer'] == 1 ? 'team member' : 'candidate';

                        $message = "{$user['first_name']} has been added to your company as a {$role}.";

                        RecentActivity::createAccountActivity($user['id'], $message, $request['prefer']);

                    }

                    if (!empty($request['register_from_app'])) {

                        $url = config('client_url.client_dashboard_url') . '/email-verified?token=' . $user['email_verify_token'];

                    } else {

                        $url = config('client_url.client_dashboard_url') . '/email-verified?token=' . $user['email_verify_token'] . '&app=azklmwosdf';

                    }

                    $user->setAppends([]);

                    if (empty($request['google_id']) && empty($request['apple_id'])) {

                        $template = EmailTemplate::getEmailTemplateByTag(Admin::VERIFIED_EMAIL);

                        $emailData = $this->prepareEmailData($user, $url, null, $template->body, $template->subject);

                        $this->sendEmailVerification($emailData, $user['email'], Admin::VERIFIED_EMAIL);

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

                        $template = EmailTemplate::getEmailTemplateByTag(Admin::VERIFIED_EMAIL);

                        $emailData = $this->prepareEmailData($checkUser, $url, null, $template->body, $template->subject);

                        $this->sendEmailVerification($emailData, $checkUser['email'], Admin::VERIFIED_EMAIL);

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

                                B2BBusinessCandidates::registerCandidate($data['id'], $checkUser['id'], $request['prefer'], Admin::DECLINED_DATA);

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

            $checkEmailVerified = User::checkEmailVerified($user['email']);

            if (!empty($checkEmailVerified)) {

                $checkEmailVerified->setAppends([]);

                return Helpers::successResponse('Your Email is verified', $checkEmailVerified);

            } else {

                return Helpers::validationResponse('Your Email is not verified');

            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function registerLastStep(RegisterLastStepRequest $request)
    {

        DB::beginTransaction();

        try {

            $getUser = User::getSingleUser($request['user_id']);

            if ($getUser) {

                $dataArray = $request->only((new User())->getFillable());

                $dataArray['step'] = 3;

                $dataArray['gender'] = $request->input('gender') === 'male' ? 0 : 1;

                tap($getUser->update($dataArray));

                PushNotification::createNotification($request['user_id']);

                $getInvite = UserInvite::getSingleInvite($getUser['email']);

                if ($getInvite) {

                    $memberCandidateInvite = UserCandidateInvite::getInviteById($getInvite['id']);

                    if ($memberCandidateInvite) {

                        $memberCandidateInvite->delete();

                    }

                }

                $getUser['two_way_auth'] = ($getUser['two_way_auth'] === Admin::TWO_WAY_AUTH_ACTIVE ? true : false);

                $getUser['app_intro_check'] = ($getUser['app_intro_check'] === Admin::INTRO_CHECK_UN_READ ? true : false);

                if ($getUser['is_admin'] == Admin::IS_B2B) {

                    $getUser->setAppends([]);

                    $data = [
                        'user' => $getUser,
                        'b2b_create_Account' => true,
                    ];

                    DB::commit();

                    return Helpers::successResponse('Complete Your maestro Signup Process', $data);

                } else {

                    $token = $this->auth->login($getUser);

                    $userInvite = UserInvite::getSingleInvite($getUser['email']);

                    UserInviteLog::deleteInvite($userInvite['id']);

                    $userTimezone = Helpers::explodeTimezoneWithHours($getUser['timezone']);

                    $signupTime = $getUser['created_at']->addMinutes($userTimezone * 60);

                    $getUser->update(['last_login' => $signupTime->format('Y-m-d H:i:s')]);

                    HaiChatHelpers::syncUserRecordWithHAi();

                    $data = [
                        'user' => $getUser,
                        'authorization' => [
                            'token' => $token,
                            'type' => 'bearer',
                        ],
                    ];

                    DB::commit();

                    return Helpers::successResponse('User logged in successfully', $data);
                }
            }

            return Helpers::validationResponse('User not found');

        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function sendSmsCode(SmsRequest $request)
    {
        try {

            $user = User::getSingleUser($request['user_id']);

            if (!empty($user)) {

                $code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

                $message = "Hi {$user['first_name']} {$user['last_name']}, your code is: {$code} for verifying your phone number.";

                $user->update(['phone' => $request['phone'], 'sms_verify_code' => $code]);

                $user->setAppends([]);

                //                $this->sns->sendSms($request['phone'], $message);

                return Helpers::successResponse('sms code send', $user);

            } else {

                return Helpers::validationResponse('Your Email is not verified');
            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function SmsCodeVerification(SmsCodeRequest $request)
    {
        try {

            $user = User::getSingleUser($request['user_id']);

            if (!empty($user)) {

                $smsCode = $user['sms_verify_code'];

                if ($request['verification_code'] == $smsCode) {

                    $user->update(['phone_verified_at' => Carbon::now()]);

                    $user->setAppends([]);

                    return Helpers::successResponse('phone number is verified', $user);

                } else {

                    return Helpers::validationResponse('Your Code is not verified');

                }

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

            if (empty($user['email_verified_at'])) {

                User::emailVerified($user['id']);

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

    public function resendEmailVerification(Request $request)
    {

        try {

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

            $checkUserEmail = User::checkEmail($request['email']);

            if (!empty($checkUserEmail)) {

                $token = User::generateToken($checkUserEmail['email']);

                if (!empty($request['change_password_from'])) {
                    $url = config('client_url.client_dashboard_url') . '/reset-password?token=' . $token['reset_password_token'] . '&change_password_from=' . $request['change_password_from'];
                } else {
                    $url = config('client_url.client_dashboard_url') . '/reset-password?token=' . $token['reset_password_token'];
                }

                $template = EmailTemplate::getEmailTemplateByTag(Admin::RESET_PASSWORD);

                $emailData = $this->prepareEmailData($checkUserEmail, $url, null, $template->body, $template->subject);

                $this->sendEmailVerification($emailData, $checkUserEmail['email'], Admin::RESET_PASSWORD);

                return Helpers::successResponse('We have emailed your password reset link!');

            } else {

                return Helpers::validationResponse('Email does not exists');
            }

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

                    Helpers::createCustomerAndSubscriptionOnStripe($checkUser);

                    $minutes = Helpers::explodeTimezoneWithHours($checkUser['timezone']);

                    $currentTime = Carbon::now()->addMinutes($minutes * 60);

                    Helpers::checkAndAddBonusCredits($checkUser, $currentTime);

                    Helpers::checkAndAddHumanOpPoints($checkUser, $currentTime);

                    if ($checkUser['last_login'] == null) {
                        $checkUser['last_login'] = $currentTime;
                    }

                    $checkUser->save();

                }

                if ($token) {

                    $user = Helpers::getUser();

                    if ($request['company_name']) {

                        $data = User::getSingleUserFromCompanyName($request['company_name']);

                        if (!empty($data)) {

                            B2BBusinessCandidates::registerCandidate($data['id'], $user['id'], $request['prefer'], Admin::DECLINED_DATA);

                            $getInvite = UserInvite::getSingleInvite($user['email']);

                            if ($getInvite) {

                                $memberCandidateInvite = UserCandidateInvite::where('invite_link_id', $getInvite->id)->where('company_id', $data['id'])->first();

                                if ($memberCandidateInvite) {

                                    $memberCandidateInvite->delete();
                                }
                            }
                        }
                    }

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

            if (!empty($request['invite_link'])) {

                $getInviteLink = UserInvite::getInviteLink($request['invite_link']);

                if ($getInviteLink['email'] != $request['email']) {

                    $loginMethod = isset($request['google_id']) ? 'Google' : 'App';

                    return Helpers::validationResponse('Invite link is not valid for this email. Please log in using ' . $loginMethod . ' with the valid email address.');

                }

            }

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

                    return Helpers::validationResponse('Your email is not verified. Kindly verify your email to continue.', $userData);
                }

                if ($user['step'] != 3) {
                    $userData = [
                        'user_id' => $user['id'],
                        'registration_step' => $user['step']
                    ];

                    return Helpers::validationResponse('Please complete all required steps in the signup process to log in.', $userData);
                }

                if (!empty($request['company_name'])) {

                    $data = User::getSingleUserFromCompanyName($request['company_name']);

                    if (!empty($data)) {

//                        if ($request['prefer'] == 1) {
//
//                            $result = Helpers::packageLimitation($data['id']);
//
//                            if ($result === true) {
//
//                                B2BBusinessCandidates::registerCandidate($data['id'], $user['id'], $request['prefer'], Admin::DECLINED_DATA);
//
//                            } else {
//
//                                return Helpers::validationResponse('Upgrade: You have reached the maximum number of Member for your account tier.');
//
//                            }
//
//                        } else {

                        B2BBusinessCandidates::registerCandidate($data['id'], $user['id'], $request['prefer'], Admin::DECLINED_DATA);

//                        }

                        $getInvite = UserInvite::getSingleInvite($user['email']);

                        if ($getInvite) {

                            $memberCandidateInvite = UserCandidateInvite::where('invite_link_id', $getInvite->id)->where('company_id', $data['id'])->first();

                            if ($memberCandidateInvite) {

                                $memberCandidateInvite->delete();
                            }
                        }
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

    public function sendPhoneOtp(SendPhoneOtpRequest $request)
    {
        try {

            $email = $request->input('email');

            $checkUserEmail = User::checkEmail($email);

            $otpNumber = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

            $template = EmailTemplate::getEmailTemplateByTag(Admin::FA_VERIFICATION_CODE);

            $emailData = $this->prepareEmailData($checkUserEmail, null, $otpNumber, $template->body, $template->subject);

            $this->sendEmailVerification($emailData, $email, Admin::FA_VERIFICATION_CODE);

            return Helpers::successResponse('Otp sent Successfully', ['otp' => $otpNumber]);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function intentionOption()
    {
        try {

            $intention_option = IntentionOption::getOptions();

            return Helpers::successResponse('success', $intention_option);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function checkUserDetail(CheckCandidate $request)
    {
        try {

            $dataResult = $request->only(['token', 'company_name', 'prefer']);

            $invite = UserInvite::getInviteLink($dataResult['token']);

            if (!empty($invite)) {

                $data = User::checkEmail($invite['email']);

                if (!empty($data)) {

                    $url = config('client_url.client_dashboard_url') . '/login?link=' . $dataResult['token'] . '&company_name=' . $dataResult['company_name'] . '&prefer=' . $dataResult['prefer'];

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

                $getInvite = UserInvite::getSingleInvite($validatedData['email']);

                if (!empty($getInvite)) {

                    $link = config('client_url.client_dashboard_url') . '/register?link=' . $getInvite['link'];

                    return response()->json(['link' => $link]);

                } else {

                    $createLink = UserInvite::sendInvite($validatedData['email']);

                    $link = config('client_url.client_dashboard_url') . '/register?link=' . $createLink['link'];

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

    public function getUserInfoForHai()
    {

        $userData = User::getUserDataForHai();

        $result = [];

        foreach ($userData as $data) {

            $getAssessment = Assessment::getLatestAssessment($data['id']);

            $optimizationPlan = $getAssessment ? ActionPlan::getUserActionPlan($data['id']) : null;

            $coreState = $getAssessment ? Assessment::getCoreState($getAssessment, $data['date_of_birth']) : null;

            $userTrait = Assessment::UserTraits($data['id']);

            $userDailyTip = UserDailyTip::where('user_id', $data['id'])->with('dailyTip')->latest()->first();

            $intention = IntentionPlan::getUserIntentionPlan($data['id']);

            $result[] = [
                'user_detail' => [
                    'name' => ($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''),
                    'email' => $data['email'] ?? '',
                    'phone' => $data['phone'] ?? '',
                    'date_of_birth' => $data['date_of_birth'] ?? '',
                    'gender' => $data['gender'] ?? '',
                    'timezone' => $data['timezone'] ?? '',
                    'plan_name' => $data['plan_name'] ?? ''
                ],
                'interval_of_life' => $coreState['interval_of_life'],
                'intention_option' => $intention,
                'assessment' => $coreState['assessment'],
                'all_traits' => $userTrait,
                'top_three_traits' => $coreState['topThreeStyles'],
                'top_two_features' => $coreState['topTwoFeatures'],
                'tertiary_features' => $coreState['tertiaryFeatures'],
                'alchemy' => $coreState['boundary'],
                'energy_center' => $coreState['topCommunication'],
                'energy_pool' => $coreState['energyPool'],
                'perception' => $coreState['perception'],
                'optimization_plan' => $optimizationPlan,
                'daily_tip' => $userDailyTip['dailyTip'] ?? '',

            ];

        }

        return Helpers::successResponse('Users Complete Data', $result);
    }

    private function prepareEmailData($user = null, $url = null, $codeNumber = null, $body = null, $subject = null)
    {
        return [
            '{$userName}' => $user['first_name'] . ' ' . $user['last_name'],
            '{$link}' => $url,
            '{$code}' => $codeNumber,
            '{$logo}' => URL::asset('assets/logos/HumanOp Logo.png'),
            '{$body}' => $body,
            '{$subject}' => $subject,
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
}
