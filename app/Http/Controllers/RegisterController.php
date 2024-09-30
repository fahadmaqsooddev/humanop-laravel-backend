<?php

namespace App\Http\Controllers;

use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\User;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Client\Register\RegisterFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Stripe\BaseStripeClient;
use App\Models\IntentionPlan\IntentionPlan;

class RegisterController extends Controller
{

    protected $user = null;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create()
    {

        $google_user = Session::get('google_user', []);

        return view('session/register', compact('google_user'));
    }

    public function store(RegisterFormRequest $request)
    {
        DB::beginTransaction();

        try {

            $dataArray = $request->only($this->user->getFillable());

            $user = User::createUser($dataArray);

            if (!empty($request['ninety_day_intention']))
            {
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
}
