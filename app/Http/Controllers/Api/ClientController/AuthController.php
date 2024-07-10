<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\LoginRequest;
use App\Http\Requests\Api\Client\RegisterRequest;
use App\Models\User;
use Hamcrest\BaseDescription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    protected $auth;

    public function __construct()
    {
        $this->middleware('auth:api')->except(['loginClient','registerClient']);

        $this->auth = Auth::guard('api');
    }

    public function loginClient(LoginRequest $request){

        try {

            $credentials = $request->only(['email','password']);

            $token = $this->auth->attempt($credentials);

            if ($token){

                $user_data = User::user(Helpers::getUser()->id);

                $data = [
                    'user' => $user_data,
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ];

                return Helpers::successResponse('User loggedIn successfully',$data);

            }else{

                return Helpers::unauthResponse('Wrong Password');
            }

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function registerClient(RegisterRequest $request){

        DB::beginTransaction();

        try {

            $user = new User();

            $request = Helpers::explodeAgeRangeIntoAge($request);

            $dataArray = $request->only($user->getFillable());

            $user = User::createClient($dataArray);

            if (!$user->hasStripeId()) {

                User::createCustomerAndSubscriptionOnStripe($user);

            }

            DB::commit();

            $token = $this->auth->login($user);

            $data = [
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ];

            return Helpers::successResponse('User register successfully', $data);

        }catch (\Exception $exception){

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }
}
