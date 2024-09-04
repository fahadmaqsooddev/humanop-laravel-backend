<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Client\Register\RegisterFormRequest;
use Illuminate\Support\Facades\DB;
use Stripe\BaseStripeClient;

class RegisterController extends Controller
{

    protected $user = null;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create()
    {
        return view('session/register');
    }

    public function store(RegisterFormRequest $request)
    {
        DB::beginTransaction();

        try {

            $dataArray = $request->only($this->user->getFillable());

            $dataArray['age_range'] = $request['age_range'];

            $user = User::createUser($dataArray);

            if (!$user->hasStripeId()) {

                User::createCustomerAndSubscriptionOnStripe($user);

            }

            session()->flash('success', 'Your account has been created.');

            Auth::login($user);

            if (isset($request['remember']) && !empty($request['remember'])) {
                setcookie("email", $request['email'], 30 * time() + 3600);
                setcookie("password", $request['password'], 30 * time() + 3600);
            } else {
                setcookie("email", "");
                setcookie("password", "");
            }

            Helpers::AfterRegistrationPayment();
            DB::commit();

            return redirect()->route('client_dashboard');

        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }
}
