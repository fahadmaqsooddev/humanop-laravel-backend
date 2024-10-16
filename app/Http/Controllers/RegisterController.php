<?php

namespace App\Http\Controllers;

use App\Helpers\Practitioner\PractitionerHelpers;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\IntentionPlan\IntentionOption;
use App\Models\User;
use App\Helpers\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Client\Register\RegisterFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
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

        $referralCode = $request->query('ref');

        $google_user = Session::get('google_user', []);
        $intention_options = IntentionOption::all();
        return view('session/register', compact('google_user', 'referralCode','intention_options'));
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

            Auth::login($user);

            DailyTip::updateUserDailyTip();

            ActionPlan::storeUserActionPlan();

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

            return redirect()->route('client_dashboard');

        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());
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

                Auth::login($userCreate);


                DailyTip::updateUserDailyTip();

                ActionPlan::storeUserActionPlan();

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

                return redirect()->to(PractitionerHelpers::makePractitionerUrl('dashboard'));

            }
        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }
}
