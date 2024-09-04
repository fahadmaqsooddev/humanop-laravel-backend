<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Client\Register\RegisterFormRequest;
use Stripe\BaseStripeClient;
use Stripe\Stripe;
use App\Models\Admin\StripeSetting\StripeSetting;
use Stripe\StripeClient;

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
        try {

            $dataArray = $request->only($this->user->getFillable());

            $dataArray['age_range'] = $request['age_range'];

            $user = User::createUser($dataArray);

            if (!$user->hasStripeId()) {

                User::createCustomerAndSubscriptionOnStripe($user);

            }

            session()->flash('success', 'Your account has been created.');
            Auth::login($user);

            $user = Helpers::getWebUser();
            $key = StripeSetting::getSingle();
            Stripe::setApiKey($key['api_key']);
            $stripe = new StripeClient($key['api_key']);

            $payment_method = $stripe->paymentMethods->attach(
                'pm_card_visa',
                ['customer' => $user['stripe_id']]
            );

            if ($user->subscriptions()->whereNull('deleted_at')->count() > 0){

                $user->subscription('main')->swapAndInvoice($request->input('plan_id'));

            }else{

                $user->newSubscription('main' , $request->input('plan_id'))->create($payment_method !== null ? $payment_method->id : '');

            }

            if (isset($request['remember']) && !empty($request['remember'])) {
                setcookie("email", $request['email'], 30 * time() + 3600);
                setcookie("password", $request['password'], 30 * time() + 3600);
            } else {
                setcookie("email", "");
                setcookie("password", "");
            }

            return redirect()->route('client_dashboard');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }
}
