<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Enums\Admin\Admin;
use App\Helpers\ActivityLogs\ActivityLogger;
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
use App\Models\Admin\Notification\Notification;
use App\Models\Admin\RecentActivity\RecentActivity;
use App\Models\Admin\Signup\SignupScreen;
use App\Models\Assessment;
use App\Models\AssessmentColorCode;
use App\Models\AssessmentDetail;
use App\Models\B2B\B2BBusinessCandidates;
use App\Models\B2B\TeamDepartmentMembers;
use App\Models\B2B\TeamDepartmentModel;
use App\Models\B2B\UserCandidateInvite;
use App\Models\Client\Connection\Connection;
use App\Models\Client\Feedback\Feedback;
use App\Models\Client\Gamification\GamificationBadgesAchievement;
use App\Models\Client\Hai\HaiThread;
use App\Models\Client\HumanOpPoints\HumanOpPoints;
use App\Models\Client\Point\Point;
use App\Models\Client\Point\PointLog;
use App\Models\Email\Email;
use App\Models\Email\EmailTemplate;
use App\Models\GenerateFile\PdfGenerate;
use App\Models\HAIChai\HaiChat;
use App\Models\HAIChai\HaiChatConversation;
use App\Models\IntentionPlan\IntentionOption;
use App\Models\Notification\PushNotification;
use App\Models\User;
use App\Models\UserInvite\UserInvite;
use App\Models\UserInvite\UserInviteLog;
use App\Services\AwsSnsServices\SnsServices;
use App\Services\FreemiumEnrollmentService;
use App\Services\GoHighLevelService;
use Carbon\Carbon;
use Dompdf\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    protected $auth;
    protected $sns;

    protected $ghl;

    public function __construct(SnsServices $sns, private FreemiumEnrollmentService $freemiumService, GoHighLevelService $ghl)
    {
        $this->middleware('auth:api')->except(['resendOtpCode', 'verifyOtpCode', 'SendInvite', 'loginClient', 'forgotPassword', 'socialLogin', 'getUserInfoForHai', 'resendEmailVerification', 'registerFirstStep', 'checkEmailVerification', 'registerLastStep', 'checkInviteLink', 'EmailVerified', 'sendPhoneOtp', 'checkUserDetail', 'sendSmsCode', 'SmsCodeVerification', 'intentionOption', 'ResendFaVerificationCode', 'onboardingScreens', 'storeUserDataFromOtherDb', 'betaBreakerClubUsers', 'haiChatHistory', 'createThreadIds']);

        $this->auth = Auth::guard('api');

        $this->sns = $sns;

        $this->ghl = $ghl;
    }

    public function onboardingScreens()
    {
        try {

            $screens = SignupScreen::allScreens();

            return Helpers::successResponse('Signup Onboarding Screens', $screens);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function registerFirstStep(RegisterFirstStepRequest $request)
    {
        DB::beginTransaction();

        try {

            // We'll use this only to access instance methods like checkEmail()
            $userModel = new User();
            $skipFields = ['gender', 'date_of_birth', 'timezone', 'phone'];
            $allFields = $request->only((new User)->getFillable());
            $dataArray = array_diff_key($allFields, array_flip($skipFields));
            // Split full_name into first_name / last_name
            $parts = explode(' ', $request->input('full_name'));
            $dataArray['first_name'] = $parts[0] ?? '';
            $dataArray['last_name'] = $parts[1] ?? '';

            // Check if this email is frozen / soft-deleted
            $checkDeleteAccount = $userModel->checkDeleteEmail($dataArray['email']);

            if (!empty($checkDeleteAccount)) {
                DB::rollBack();
                return Helpers::validationResponse(
                    'Your account associated with this email has been frozen. Please contact our technical support team for assistance.'
                );
            }

            // Check if a user with this email already exists
            $checkUser = $userModel->checkEmail($dataArray['email']);

            /*
             |---------------------------------------------------------
             | CASE 1: NEW USER (email not found in system)
             |---------------------------------------------------------
             */
            if (empty($checkUser)) {

                // Create the user via your existing flow
                if ($request['b2b_invite'] == 1) {
                    $user = $userModel->createFirstStep(
                        $dataArray,
                        $request['google_id'],
                        $request['apple_id'],
                        true // invited to b2b
                    );
                } else {
                    $user = $userModel->createFirstStep(
                        $dataArray,
                        $request['google_id'],
                        $request['apple_id'],
                        false,           // not a b2b invite
                        $request['ref']  // referral code / source
                    );
                }

                // Safety: make sure createFirstStep actually returned a persisted Eloquent model
                if (!($user instanceof User) || empty($user->id)) {
                    DB::rollBack();
                    return Helpers::serverErrorResponse('User creation failed: user was not persisted.');
                }

                // Award initial credits / points
                if ($user['beta_breaker_club'] == Admin::BETA_BREAKER_CLUB) {
                    Point::addPoints(Admin::BREAKER_CREDITS, $user);
                } else {
                    Point::addPoints(Admin::FREEMIUM_CREDITS, $user);
                }
                Point::addPoints(Admin::CORE_CREDITS, $user, 1);

                Log::info(print_r($user, true));
                // Sync to HAi downstream system
                HaiChatHelpers::syncUserRecordWithHAi($user);

                // If they provided a company_name, wire B2B candidate / activity
                if (!empty($request['company_name'])) {

                    $companyOwner = User::getSingleUserFromCompanyName($request['company_name']);

                    B2BBusinessCandidates::registerCandidate(
                        $companyOwner['id'],
                        $user['id'],
                        $request['prefer'],
                        Admin::DECLINED_DATA
                    );

                    $role = $request['prefer'] == 1 ? 'team member' : 'candidate';

                    $message = "{$user['first_name']} has been added to your company as a {$role}.";
                    RecentActivity::createAccountActivity(
                        $user['id'],
                        $message,
                        $request['prefer']
                    );

                    if (!empty($request['team_name']) && !empty($request['department_name'])) {
                        $getDepartment = TeamDepartmentModel::getTeamRecord(
                            $request['team_name'],
                            $companyOwner['id']
                        );

                        TeamDepartmentMembers::createTeamMember(
                            $getDepartment['id'],
                            $user['id']
                        );
                    }
                }


                if (!empty($request['register_from_app'])) {
                    $verifyUrl = config('client_url.client_dashboard_url') .
                        '/email-verified?token=' . $user['email_verify_token'];
                } else {
                    $verifyUrl = config('client_url.client_dashboard_url') .
                        '/email-verified?token=' . $user['email_verify_token'] .
                        '&app=azklmwosdf';
                }
                // Strip any appended accessors so we don't leak internals
                $user->setAppends([]);

                // Send verification email ONLY if not social login
                if (empty($request['google_id']) && empty($request['apple_id'])) {
                    $template = EmailTemplate::getEmailTemplateByTag(Admin::VERIFIED_EMAIL);
                    $emailData = $this->prepareEmailData(
                        $user,
                        $verifyUrl,
                        null,
                        $template->body,
                        $template->subject
                    );

                    $this->sendEmailVerification(
                        $emailData,
                        $user['email'],
                        Admin::VERIFIED_EMAIL,
                        "Verify Your Email Address"
                    );
                }

                /*
                 |---------------------------------------------------------
                 | Attach Freemium Stripe subscription
                 |---------------------------------------------------------
                 |
                 | VERY IMPORTANT:
                 | - The user is now persisted and has an ID.
                 | - We call freemium enrollment AFTER user creation,
                 |   inside this same transaction but AFTER we know
                 |   $user is a real Eloquent model.
                 */

                // Refresh the model so we have latest DB state
                $user->refresh();

                // Enroll the user into freemium (creates Stripe customer if missing,
                // creates $0 freemium subscription in Stripe, mirrors to subscriptions table,
                // sets user->plan='freemium', etc.)
                $this->freemiumService->enroll($user);

                DB::commit();

                // Return success payload
                return Helpers::successResponse('Verification Email Send successfully', [
                    'authorization' => [
                        'user' => $user,
                        'status' => true,
                        'type' => 'bearer',
                    ],
                ]);
            }

            /*
             |---------------------------------------------------------
             | CASE 2: USER ALREADY EXISTS
             |---------------------------------------------------------
             |
             | We do NOT enroll freemium again here.
             | We just handle:
             |   - Email verification resend
             |   - "finish last step" logic
             */

            $checkEmailVerified = User::checkEmailVerified($checkUser['email']);

            if (empty($checkEmailVerified)) {

                // build verification URL again for existing user
                if (!empty($request['register_from_app'])) {
                    $verifyUrl = config('client_url.client_dashboard_url') .
                        '/email-verified?token=' . $checkUser['email_verify_token'];
                } else {
                    $verifyUrl = config('client_url.client_dashboard_url') .
                        '/email-verified?token=' . $checkUser['email_verify_token'] .
                        '&app=azklmwosdf';
                }

                $template = EmailTemplate::getEmailTemplateByTag(Admin::VERIFIED_EMAIL);
                $emailData = $this->prepareEmailData(
                    $checkUser,
                    $verifyUrl,
                    null,
                    $template->body,
                    $template->subject
                );

                $this->sendEmailVerification(
                    $emailData,
                    $checkUser['email'],
                    Admin::VERIFIED_EMAIL,
                    null
                );

                $checkUser->setAppends([]);

                DB::commit();

                return Helpers::successResponse(
                    'Your email is not verified. Verification email sent.',
                    [
                        'authorization' => [
                            'user' => $checkUser,
                            'email' => 'not verified',
                            'status' => true,
                            'type' => 'bearer',
                        ],
                    ]
                );
            } else {

                $checkLastStep = User::checkLastStep($checkUser['email']);

                if ($checkLastStep && $checkLastStep['step'] == 3) {

                    if (!empty($request['company_name'])) {
                        $companyOwner = User::getSingleUserFromCompanyName($request['company_name']);

                        B2BBusinessCandidates::registerCandidate(
                            $companyOwner['id'],
                            $checkUser['id'],
                            $request['prefer'],
                            Admin::DECLINED_DATA
                        );
                    }

                    DB::commit();

                    return Helpers::validationResponse(
                        'An account with this email already exists. Please log in to continue.'
                    );

                } else {

                    $checkLastStep->setAppends([]);

                    DB::commit();

                    return Helpers::successResponse('kindly complete your last step', [
                        'authorization' => [
                            'user' => $checkLastStep,
                            'status' => true,
                            'type' => 'bearer',
                        ],
                    ]);
                }
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

                    $token = $this->auth->login($getUser);

                    $userInvite = UserInvite::getSingleInvite($getUser['email']);

                    if (!empty($userInvite)) {

                        UserInviteLog::deleteInvite($userInvite['id']);

                    }

                    $userTimezone = Helpers::explodeTimezoneWithHours($getUser['timezone']);

                    $signupTime = $getUser['created_at']->addMinutes($userTimezone * 60);

                    $getUser->update(['last_login' => $signupTime->format('Y-m-d H:i:s')]);

                    HaiChatHelpers::syncUserRecordWithHAi();

                    $data = [
                        'user' => $getUser,
                        'b2b_create_Account' => true,
                        'authorization' => [
                            'token' => $token,
                            'type' => 'bearer',
                        ],
                    ];

                    DB::commit();

                    return Helpers::successResponse('Complete Your maestro Signup Process', $data);

                } else {

                    $token = $this->auth->login($getUser);

                    $userInvite = UserInvite::getSingleInvite($getUser['email']);

                    if (!empty($userInvite)) {

                        UserInviteLog::deleteInvite($userInvite['id']);

                    }

                    $userTimezone = Helpers::explodeTimezoneWithHours($getUser['timezone']);

                    $signupTime = $getUser['created_at']->addMinutes($userTimezone * 60);

                    $getUser->update(['last_login' => $signupTime->format('Y-m-d H:i:s')]);

                    HaiChatHelpers::syncUserRecordWithHAi();

                    $signupMethod = $getUser->google_id ? 'Google' : ($getUser->apple_id ? 'Apple' : 'Email');

                    ActivityLogger::addLog('Signup', "User Signup by {$signupMethod}");

                    $this->ghl->createUser($getUser);

                    $data = [
                        'user' => $getUser,
                        'authorization' => [
                            'token' => $token,
                            'type' => 'bearer',
                        ],
                    ];

                    DB::commit();

                    return Helpers::successResponse('User signed up successfully.', $data);

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

                    $token = $this->auth->login($user);

                    $data = [
                        'user' => $user,
                        'authorization' => [
                            'token' => $token,
                            'type' => 'bearer',
                        ]
                    ];

                    return Helpers::successResponse('phone number is verified', $data);

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

//            $userInvite = UserInvite::getSingleInvite($user['email']);

            $data = [
                'user' => $user,
//                'user_invite' => $userInvite['link'],
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

            $template = EmailTemplate::getEmailTemplateByTag(Admin::VERIFIED_EMAIL);

            $emailData = $this->prepareEmailData($user, $baseUrl, null, $template->body, $template->subject);

            $this->sendEmailVerification($emailData, $user['email'], Admin::VERIFIED_EMAIL, "Verify Your Email Address");

            return Helpers::successResponse('Resend email sent successfully!');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function logoutClient()
    {

        try {

            $this->auth->logout();

            ActivityLogger::addLog('Logout', "User logged out successfully");

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

                $this->sendEmailVerification($emailData, $checkUserEmail['email'], Admin::RESET_PASSWORD, null);

                return Helpers::successResponse('We have emailed your password reset link!');

            } else {

                return Helpers::validationResponse('Email does not exists');
            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }


    // Add this private method in your controller
    private function handleUserLoginIp(User $user, Request $request): string
    {
        // Get IP from request or fallback to request IP
        $ipAddress = $request->input('ip_address') ?? $request->ip();

        // Unique cache key per user
        $cacheKey = "user_ip_{$user->id}";

        // Store IP in cache for 24 hours
        Cache::put($cacheKey, $ipAddress, now()->addHours(24));

        return $ipAddress;
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

            if (!empty($request['login_from_beta']) && ($request['login_from_beta'] === true || $request['login_from_beta'] === 'true')) {

                if (
                    $checkUser->beta_breaker_club == Admin::BETA_BREAKER_CLUB_NOT &&
                    $checkUser->plan !== 'bb_onetime' &&
                    $checkUser->has_bb_onetime != Admin::BB_ONETIME
                ) {
                    return Helpers::validationResponse('You cannot login because you are not a member of the Beta Breaker Club.');
                }
            }

            if (empty($checkUser)) {

                return Helpers::validationResponse("These credentials do not match our records.");

            } else if ($checkUser && $checkUser['email_verified_at'] == null) {

                $userInvite = UserInvite::getSingleInvite($checkUser['email']);

                $userData = [
                    'user_id' => $checkUser['id'],
                    'user_name' => $checkUser['first_name'] . ' ' . $checkUser['last_name'],
                    'email' => $checkUser['email'],
                    'registration_step' => $checkUser['step'],
                    'user_invite' => $userInvite['link'] ?? null

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

            } else if ($checkUser and $checkUser['two_way_auth'] == Admin::TWO_WAY_AUTH_ACTIVE) {

                $otpNumber = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

                $template = EmailTemplate::getEmailTemplateByTag(Admin::FA_VERIFICATION_CODE);

                $emailData = $this->prepareEmailData($checkUser, null, $otpNumber, $template->body, $template->subject);

                $this->sendEmailVerification($emailData, $checkUser['email'], Admin::FA_VERIFICATION_CODE, $template->name);

                $checkUser->update(['sms_verify_code' => $otpNumber]);

                DB::commit();

                $userData = [
                    'user_id' => $checkUser['id'],
                    'user_name' => $checkUser['first_name'] . ' ' . $checkUser['last_name'],
                    'email' => $checkUser['email'],
                    'two_way_auth' => $checkUser['two_way_auth'] == Admin::TWO_WAY_AUTH_ACTIVE ? true : false,
                ];

                return Helpers::successResponse('Otp sent Successfully', $userData);


            } else {

                $remember_me = $request['remember'] == 'true' ? true : false;

                if ($remember_me == true) {

                    $token = $this->auth->attempt($credentials, $remember_me);

                } else {

                    $token = $this->auth->attempt($credentials);

                }

                if ($token) {

                    Helpers::createCustomerAndSubscriptionOnStripe($checkUser);

                    $minutes = Helpers::explodeTimezoneWithHours($checkUser['timezone']);

                    $currentTime = Carbon::now()->addMinutes($minutes * 60);

                    Helpers::checkAndAddBonusCredits($checkUser, $currentTime);

                    Helpers::checkAndAddHumanOpPoints($checkUser, $currentTime);

                    $checkUser->update(['last_login' => Carbon::now()]);

                    if ($request['company_name']) {

                        $data = User::getSingleUserFromCompanyName($request['company_name']);

                        if (!empty($data)) {

                            B2BBusinessCandidates::registerCandidate($data['id'], $checkUser['id'], $request['prefer'], Admin::DECLINED_DATA);

                            $getInvite = UserInvite::getSingleInvite($checkUser['email']);

                            if ($getInvite) {

                                $memberCandidateInvite = UserCandidateInvite::where('invite_link_id', $getInvite->id)->where('company_id', $data['id'])->first();

                                if ($memberCandidateInvite) {

                                    $memberCandidateInvite->delete();
                                }
                            }
                        }
                    }

                    User::updateUserIsFeedback();

                    HaiChatHelpers::syncUserRecordWithHAi();

                    User::checkBanner();

                    User::LastLoginWith($request);

                    $checkUser['two_way_auth'] = ($checkUser['two_way_auth'] === Admin::TWO_WAY_AUTH_ACTIVE ? true : false);

                    $checkUser['app_intro_check'] = ($checkUser['app_intro_check'] === Admin::INTRO_CHECK_UN_READ ? true : false);

                    $signupMethod = $checkUser->google_id ? 'Google' : ($checkUser->apple_id ? 'Apple' : 'Email');

                    ActivityLogger::addLog('Login', "User loggedIn by Email");
                    $ipAddress = $this->handleUserLoginIp($checkUser, $request);
                    $data = [
                        'user' => $checkUser,
                        'ip_address' => $ipAddress, // <-- return IP without saving
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

                if (!empty($request['login_from_beta']) && ($request['login_from_beta'] === true || $request['login_from_beta'] === 'true')) {

                    if (
                        $user->beta_breaker_club == Admin::BETA_BREAKER_CLUB_NOT &&
                        $user->plan !== 'bb_onetime' &&
                        $user->has_bb_onetime != Admin::BB_ONETIME
                    ) {
                        return Helpers::validationResponse('You cannot login because you are not a member of the Beta Breaker Club.');
                    }
                }

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

                } else if ($user and $user['two_way_auth'] == Admin::TWO_WAY_AUTH_ACTIVE) {

                    $otpNumber = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

                    $template = EmailTemplate::getEmailTemplateByTag(Admin::FA_VERIFICATION_CODE);

                    $emailData = $this->prepareEmailData($user, null, $otpNumber, $template->body, $template->subject);

                    $this->sendEmailVerification($emailData, $user['email'], Admin::FA_VERIFICATION_CODE, $template->name);

                    $user->update(['sms_verify_code' => $otpNumber]);

                    DB::commit();

                    $userData = [
                        'user_id' => $user['id'],
                        'user_name' => $user['first_name'] . ' ' . $user['last_name'],
                        'email' => $user['email'],
                        'two_way_auth' => $user['two_way_auth'] == Admin::TWO_WAY_AUTH_ACTIVE ? true : false,
                    ];

                    return Helpers::successResponse('Otp sent Successfully', $userData);


                }

                if (!empty($request['company_name'])) {

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

                $token = $this->auth->login($user);

                $updateUser = User::updateUserIsFeedback();

                $updateUser['two_way_auth'] = ($updateUser['two_way_auth'] === Admin::TWO_WAY_AUTH_ACTIVE ? true : false);

                $updateUser['app_intro_check'] = ($updateUser['app_intro_check'] === Admin::INTRO_CHECK_UN_READ ? true : false);

                HaiChatHelpers::syncUserRecordWithHAi();

                User::checkBanner();

                User::LastLoginWith($request);

                $signupMethod = $updateUser->google_id ? 'Google' : ($updateUser->apple_id ? 'Apple' : 'Email');

                ActivityLogger::addLog('Login', "User loggedIn by {$signupMethod}");
                $ipAddress = $this->handleUserLoginIp($user, $request);
                $data = [
                    'user' => $updateUser,
                    'ip_address' => $ipAddress,
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

            $this->sendEmailVerification($emailData, $email, Admin::FA_VERIFICATION_CODE, null);

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

        $failedUsers = [];

        foreach ($userData as $data) {

            $response = HaiChatHelpers::syncUserRecordWithHAi($data);

            if (!$response) {

                $failedUsers[] = $data['id'];

            }

        }

        return Helpers::successResponse('All Users data sync', ['failed_users' => $failedUsers]);
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

    private function sendEmailVerification($emailData, $recipientEmail, $name, $subject)
    {
        $emailTemplate = EmailTemplate::getTemplate($emailData, $name);

        Email::sendEmailVerification(
            ['content' => $emailTemplate],
            $recipientEmail,
            'emails.Email_Template',
            $subject ? $subject : $name
        );
    }

    public function twoFactorAuthentication()
    {

        $user = User::twoFactorAuth(Helpers::getUser());

        if ($user['two_way_auth'] == Admin::TWO_WAY_AUTH_ACTIVE) {

            return Helpers::successResponse('Two factor authentication is active.');

        } else {

            return Helpers::successResponse('Two factor authentication is inactive.');

        }

    }

    public function ResendFaVerificationCode(Request $request)
    {
        try {

            $user = User::getSingleUser($request['user_id']);

            if (!$user) {
                return Helpers::validationResponse('User not found or email is not verified');
            }

            $userUpdate = User::SmsCodeCreate($user['id']);

            $template = EmailTemplate::getEmailTemplateByTag(Admin::FA_VERIFICATION_CODE);

            $emailData = $this->prepareEmailData($userUpdate, null, $userUpdate['b2b_sms_verify_code'], $template->body, $template->subject);

            $this->sendEmailVerification($emailData, $userUpdate['email'], Admin::FA_VERIFICATION_CODE, $template->name);

            return Helpers::successResponse('A new two-factor authentication code has been sent to your email');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function storeUserDataFromOtherDb(Request $request)
    {
        try {

            DB::beginTransaction();

            $response = Http::get('https://beta.humanoptech.com/api/user-all-data-fetch', [

                'user_id' => $request['user_id'],

            ]);

            if ($response->successful()) {

                $json = $response->json();

                $fetchUserData = $json['result']['data'];

                if ($fetchUserData['user']) {

                    $user = User::createFetchUserData($fetchUserData['user']);

                }

                if ($fetchUserData['assessment']) {

                    foreach ($fetchUserData['assessment'] as $assessment) {

                        $assessment['user_id'] = $user['id'];

                        $assessment = Assessment::createFetchUserAssessment($assessment);

                        if ($fetchUserData['assessment_detail']) {

                            foreach ($fetchUserData['assessment_detail'] as $assessmentDetail) {

                                AssessmentDetail::createFetchUserAssessmentDetail($assessment['id'], $user['id'], $assessmentDetail);

                            }

                        }

                        if ($fetchUserData['assessment_color_code']) {

                            foreach ($fetchUserData['assessment_color_code'] as $assessmentColorCode) {

                                AssessmentColorCode::createFetchUserAssessmentColorCode($assessment['id'], $assessmentColorCode);

                            }

                        }

                        if ($fetchUserData['pdf_generate_data']) {

                            foreach ($fetchUserData['pdf_generate_data'] as $pdfGenerateData) {

                                PdfGenerate::createFetchUserPdfGenerate($assessment['id'], $user['id'], $pdfGenerateData);

                            }

                        }

                        if ($fetchUserData['daily_tip']) {

                            UserDailyTip::createUserFetchDailtTip($assessment['id'], $user['id'], $fetchUserData['daily_tip']);

                        }

                        GamificationBadgesAchievement::addBadgeAfterCompleteAssessment($user['id']);

                        HumanOpPoints::addPointsAfterCompleteAssessment($user);

                        Point::addPoints(Admin::FREEMIUM_CREDITS, $user);

                        Point::addPoints(Admin::CORE_CREDITS, $user, 1);

                    }

                }

                if ($fetchUserData['connections']) {

                    Connection::createUserFetchConnection($user['id'], $fetchUserData['connections']);

                }

                if ($fetchUserData['notification']) {

                    Notification::createUserFetchNotification($user['id'], $fetchUserData['notification']);

                }

                if ($fetchUserData['feedback']) {

                    Feedback::createUserFetchFeedback($user['id'], $fetchUserData['feedback']);
                }

                if ($fetchUserData['hai_chat_conversation']) {

                    HaiChatConversation::createUserFetchChatConversation($user['id'], $fetchUserData['hai_chat_conversation']);

                }

                if ($fetchUserData['push_notification']) {

                    PushNotification::createUserFetchPushNotification($user['id'], $fetchUserData['push_notification']);
                }

                if ($fetchUserData['points']) {

                    Point::createUserFetchPoints($user['id'], $fetchUserData['points']);
                }

                if ($fetchUserData['point_log']) {

                    PointLog::createUserFetchPointLog($user['id'], $fetchUserData['point_log']);
                }

                HaiChatHelpers::syncUserRecordWithHAi($user);

                DB::commit();

                return Helpers::successResponse('User Data Created Successfully');

            }

            DB::rollBack();

            return Helpers::serverErrorResponse('Something went wrong');

        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }


    public function betaBreakerClubUsers(Request $request)
    {
        try {

            DB::beginTransaction();

            $response = Http::get('https://beta.humanoptech.com/api/fetch-all-user-email');

            if ($response->successful()) {

                $json = $response->json();

                $fetchUserEmails = $json['result']['data'];

                foreach ($fetchUserEmails as $email) {

                    User::checkBetaBreaker($email);

                }

                DB::commit();

                return Helpers::successResponse('User Data Created Successfully');

            }

            DB::rollBack();

            return Helpers::serverErrorResponse('Something went wrong');

        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }


    public function haiChatHistory()
    {
        try {

            $users = User::get();

            $data = [];

            foreach ($users as $user) {

                // ✅ Current user ke chats lo
                $userChats = HaiChat::getSingleUserChats($user->id);

                if (count($userChats) > 0) {
                    $data[] = [
                        'user_id' => $user->id,
                        'chats' => $userChats->map(function ($chat) {
                            return [
                                'query' => $chat->query,
                                'answer' => $chat->answer
                            ];
                        })->values(),
                    ];
                }

            }

            return Helpers::successResponse('HAI CHAT status fetched successfully', $data);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function createThreadIds()
    {
        try {

            DB::beginTransaction();

            $threads = HaiChatHelpers::createThreadIds();

            foreach ($threads as $thread) {

                $new_thread = HaiThread::createThreadIds($thread);

                HaiChat::createChatThreadId($thread, $new_thread['id']);

            }

            DB::commit();

            return Helpers::successResponse('HAI CHAT status fetched successfully');

        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

}
