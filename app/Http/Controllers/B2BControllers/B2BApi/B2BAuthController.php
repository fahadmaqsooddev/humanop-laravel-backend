<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\LoginRequest;
use App\Http\Requests\B2B\getBusinessSubStrategyRequest;
use App\Models\B2B\BusinessStrategies;
use App\Models\B2B\BusinessSubStrategies;
use App\Models\User;
use App\Models\UserInvite\UserInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\B2B\RegisterRequest;
use App\Http\Requests\B2B\AddMemberRequest;
use App\Http\Requests\B2B\UpdateB2bProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;


class B2BAuthController extends Controller
{

    protected $auth;

    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth:api')->except(['b2bSignup', 'businessStrategies', 'getBusinessSubStrategies']);

        $this->auth = Auth::guard('api');

        $this->user = $user;
    }

    public function b2bSignup(RegisterRequest $request)
    {

        try {

            $dataArray = $request->only($this->user->getFillable());

            $parts = explode(' ', $request->input('full_name'));

            $dataArray['first_name'] = $parts[0] ?? '';

            $dataArray['last_name'] = $parts[1] ?? '';

            $authorizedUser = UserInvite::getSingleInvite($dataArray['email']);

            if (!empty($authorizedUser)) {

                $checkDeleteAccount = $this->user->checkDeleteEmail($dataArray['email']);

                if (!empty($checkDeleteAccount)) {

                    return Helpers::validationResponse('Your account associated with this email has been frozen. Please contact our technical support team for assistance.');
                }

                $checkUser = $this->user->checkEmail($dataArray['email']);

                if (!empty($checkUser))
                {
                    return Helpers::validationResponse('An account with this email already exists. Please log in to continue.');

                }else{

                    $b2b_user = $this->user->createB2BSignup($dataArray);

                    Helpers::createClientsOnOneSignal($b2b_user['id']);

                    $b2b_user->setAppends([]);

                    $token = $this->auth->login($b2b_user);

                    return Helpers::successResponse('User registered successfully', [
                        'authorization' => [
                            'user' => $b2b_user,
                            'token' => $token,
                            'status' => true,
                            'type' => 'bearer',
                        ],
                    ]);
                }

            } else {

                return Helpers::validationResponse('You are not recognized. Please check the invite link or contact support.');
            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }

    public function businessStrategies()
    {
        try {

            $businessStrategies = BusinessStrategies::allBusinessStrategies();

            return Helpers::successResponse('Business Strategies', $businessStrategies);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());


        }
    }

    public function getBusinessSubStrategies(getBusinessSubStrategyRequest $request)
    {
        try {

            if (!empty($request['business_id'])) {

                $businessStrategies = BusinessSubStrategies::getSubStrategies($request['business_id']);

                return Helpers::successResponse('Business Sub Strategies', $businessStrategies);

            } else {

                return Helpers::validationResponse('Business Sub Strategies not found');

            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }


    public function ProfileUpdate(UpdateB2bProfile $request){
        try {

            $request = Helpers::explodeAgeRangeIntoAge($request);

            
            if ($request) {

                $dataArray = $request->only(['first_name', 'last_name', 'phone', 'date_of_birth', 'gender', 'timezone']);
                $updated_user = User::updateUserProfile($dataArray);
                return Helpers::successResponse('User updated successfully', $updated_user);
            } else {
                return Helpers::forbiddenResponse('Please Filled Data');
            }


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

}
