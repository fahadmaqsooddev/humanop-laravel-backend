<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Client\Register\RegisterFormRequest;


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

            return redirect()->route('client_dashboard');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }
}
