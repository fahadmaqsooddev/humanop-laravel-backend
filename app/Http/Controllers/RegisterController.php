<?php

namespace App\Http\Controllers;

use App\Helpers\OpenRouterHelper;
use App\Helpers\Practitioner\PractitionerHelpers;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\Email\Email;
use App\Models\Email\EmailTemplate;
use App\Models\IntentionPlan\IntentionOption;
use App\Models\User;
use App\Helpers\Helpers;
use App\Models\UserInvite\UserInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Client\Register\RegisterFormRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Stripe\BaseStripeClient;
use App\Models\IntentionPlan\IntentionPlan;

class RegisterController extends Controller
{

    protected $user = null;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create(Request $request)
    {


        $inviteLink = $request->query('link');

        if (!empty($inviteLink))
        {
            $invite = UserInvite::getInviteLink($inviteLink);

            if (!empty($invite))
            {
                $referralCode = $request->query('ref');

                $google_user = Session::get('google_user', []);
                $intention_options = IntentionOption::all();
                return view('session/register', compact('google_user', 'referralCode', 'intention_options', 'invite'));
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

    public function practitionerRegister(Request $request, $slug, $slug2)
    {
        try {

            $user = User::where('first_name', $slug)->where('last_name', $slug2)->where('is_admin', 4)->exists();

            if ($user) {

                $referralCode = $request->query('ref');

                $google_user = Session::get('google_user', []);

                $intention_options = IntentionOption::all();

                return view('session/register', compact('google_user', 'referralCode', 'slug', 'slug2', 'intention_options'));
            } else {
                return redirect()->to('/' . $slug . '/' . $slug2 . '/login')->withErrors(['msgError' => 'This Practitioner does not exist']);
            }

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }

    public function store(RegisterFormRequest $request)
    {
        DB::beginTransaction();

        try {

            $dataArray = $request->only($this->user->getFillable());

            $checkUser = User::checkDeleteEmail($dataArray['email']);

            if (empty($checkUser))
            {
                $user = User::createUser($dataArray);

                if ($request->referralCode) {
                    $referredBy = User::where('referral_code', $request->referralCode)->first();
                    if ($referredBy) {
                        $user->update(['referred_by' => $referredBy->id]);
                    }
                }

                if (!empty($request['ninety_day_intention'])) {
                    IntentionPlan::createIntentionPlan($user['id'], $request['ninety_day_intention']);
                }

                Helpers::createCustomerAndSubscriptionOnStripe($user);

                if (isset($request['remember']) && !empty($request['remember'])) {
                    setcookie("email", $request['email'], 30 * time() + 3600);
                    setcookie("password", $request['password'], 30 * time() + 3600);
                }
                else {
                    setcookie("email", "");
                    setcookie("password", "");
                }

                if (empty($request['google_id']))
                {
                    $baseUrl = url('/check-email?token='. $user['email_verify_token']);
                    $logoUrl = URL::asset('assets/logos/HumanOp Logo.png');
                    $privacyUrl = url('/privacy-policy');
                    $serviceUrl = url('/term-of-service');

                    $data = [
                        '{$userName}' => $user['first_name'] .' ' . $user['last_name'],
                        '{$link}' =>  $baseUrl,
                        '{$logo}' => $logoUrl,
                        '{$service}' => $serviceUrl,
                        '{$privacy}' => $privacyUrl,
                    ];

                    $email_template = EmailTemplate::getTemplate($data, 'email-verification');

                    Email::sendEmailVerification(['content' => $email_template],$user['email'],'emails.Email_Template', 'Email Verification');

                    Session::put('userId', $user['id']);

                    DB::commit();

                    return redirect()->to('/email-verify?token=' . $user['email_verify_token']);

                }
                else
                {

                    User::emailVerified($user['id']);

                    $remember_me = $request->has('remember') ? true : false;

                    Auth::login($user, $remember_me);

                    session()->flash('success', 'Your account has been created.');

                    Session::forget('google_user');

                    DB::commit();

                    return redirect()->route('client_dashboard');

                }
            }
            else
            {
                $invite = UserInvite::getInviteLinkUsingEmail($checkUser['email']);

                session()->flash('error', 'Your account associated with this email has been frozen. Please contact our technical support team for assistance.');

                return redirect()->to('register?link=' . $invite['link']);

            }


        } catch (\Exception $exception) {

            DB::rollBack();

            return redirect()->route('create')->withErrors(['server_error' => Helpers::serverErrorResponse($exception->getMessage())]);


        }
    }

    public function registerClientToPractitioner(RegisterFormRequest $request)
    {
        DB::beginTransaction();

        try {

            $user = User::where('first_name', $request['slug'])->where('last_name', $request['slug2'])->where('is_admin', 4)->first();

            if ($user) {

                $dataArray = $request->only($this->user->getFillable());

                $userCreate = User::createPractitionerUser($dataArray, $user['id']);

                if ($request->referralCode) {
                    $referredBy = User::where('referral_code', $request->referralCode)->first();
                    if ($referredBy) {
                        $userCreate->update(['referred_by' => $referredBy->id]);
                    }
                }

                if (!empty($request['ninety_day_intention'])) {
                    IntentionPlan::createIntentionPlan($userCreate['id'], $request['ninety_day_intention']);
                }

                Helpers::createCustomerAndSubscriptionOnStripe($userCreate);

                $baseUrl = PractitionerHelpers::makePractitionerUrl('check-email', ['id' => $userCreate['id']]);
                $logoUrl = URL::asset('assets/logos/HumanOp Logo.png');
                $privacyUrl = url('/privacy-policy');
                $serviceUrl = url('/term-of-service');

                $data = [
                    '{$userName}' => $userCreate['first_name'] .' ' . $userCreate['last_name'],
                    '{$link}' =>  $baseUrl,
                    '{$logo}' => $logoUrl,
                    '{$service}' => $serviceUrl,
                    '{$privacy}' => $privacyUrl,
                ];

                $email_template = EmailTemplate::getTemplate($data, 'email-verification');

                Email::sendEmailVerification(['content' => $email_template], $userCreate['email'],'emails.Email_Template', 'Email Verification');

                if (isset($request['remember']) && !empty($request['remember'])) {
                    setcookie("email", $request['email'], 30 * time() + 3600);
                    setcookie("password", $request['password'], 30 * time() + 3600);
                } else {
                    setcookie("email", "");
                    setcookie("password", "");
                }

                session()->flash('success', 'Your account has been created.');

                DB::commit();

                Session::forget('google_user');

                return redirect()->to(PractitionerHelpers::makePractitionerUrl('email-verify'));

            } else {
                return view('errors/404');
            }
        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function practitionerEmailVerify()
    {
        try {

            $auth = Helpers::getWebUser();

            if ($auth)
            {
                return redirect()->to(PractitionerHelpers::makePractitionerUrl('dashboard'));

            }else
            {
                return view('session/email-verify');
            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function resendEmailVerification(Request $request)
    {
        try {

            $token = $request->query('token');

            $user = User::where('email_verify_token', $token)->first();

            if (!empty($user) && $user['email_verified_at'] == null)
            {
                $baseUrl = url('/check-email', $user['email_verify_token']);
                $logoUrl = URL::asset('assets/logos/HumanOp Logo.png');
                $privacyUrl = url('/privacy-policy');
                $serviceUrl = url('/term-of-service');

                $data = [
                    '{$userName}' => $user['first_name'] .' ' . $user['last_name'],
                    '{$link}' =>  $baseUrl,
                    '{$logo}' => $logoUrl,
                    '{$service}' => $serviceUrl,
                    '{$privacy}' => $privacyUrl,
                ];

                $email_template = EmailTemplate::getTemplate($data, 'email-verification');

                Email::sendEmailVerification(['content' => $email_template], $user['email'],'emails.Email_Template', 'Email Verification');

                return redirect('/email-verify')->with(['success' => 'Resend email sent successfully!']);
            }
            else
            {
                return redirect()->route('client_dashboard')->with(['success' => 'You are already verified']);

            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function practitionerCheckEmail($id = null)
    {

        $id = last(request()->segments());

        $user = User::getSingleUser($id);

        if ($user)
        {

            User::emailVerified($user['id']);

            Auth::login($user);

            return redirect()->to(PractitionerHelpers::makePractitionerUrl('dashboard'));

        } else
        {
            return redirect()->to('/register');
        }
    }
}
