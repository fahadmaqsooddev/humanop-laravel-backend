<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\B2B\B2BRegisterfirstStep;
use App\Http\Requests\B2B\getBusinessSubStrategyRequest;
use App\Http\Requests\B2B\updateB2BProfileRequest;
use App\Models\B2B\B2BIntentionOption;
use App\Models\B2B\BusinessStrategies;
use App\Models\B2B\BusinessSubStrategies;
use App\Models\B2B\B2BSupport;
use App\Models\B2B\SelectIntentionOption;
use App\Models\User;
use App\Models\UserInvite\UserInvite;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\B2B\RegisterRequest;
use App\Http\Requests\B2B\B2BSupportRequest;
use App\Models\Upload\Upload;
use Illuminate\Http\Request;
use function Symfony\Component\Translation\t;

class B2BAuthController extends Controller
{

    protected $auth;

    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth:api')->except(['b2bSignup','b2bRegisterFirstStep', 'businessStrategies', 'b2bAccountCheck', 'getBusinessSubStrategies', 'AllIntentions']);

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

                if (!empty($checkUser)) {
                    return Helpers::validationResponse('An account with this email already exists. Please log in to continue.');

                } else {


                    $b2b_user = $this->user->createB2BSignup($dataArray);

                    if (!empty($request['intention_option_id'])) {

                        SelectIntentionOption::storeUserIntentions($b2b_user['id'], $request['intention_option_id']);

                    }

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


    public function ProfileUpdate(updateB2BProfileRequest $request)
    {
        try {

            $request = Helpers::explodeAgeRangeIntoAge($request);

            if ($request) {

                $dataArray = $request->only(['first_name', 'last_name', 'phone', 'date_of_birth', 'gender', 'timezone', 'company_name', 'password']);

                $updated_user = User::updateUserProfile($dataArray);

                return Helpers::successResponse('User updated successfully', $updated_user);

            } else {

                return Helpers::forbiddenResponse('Please Filled Data');
            }


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }


    public function Support(B2BSupportRequest $request)
    {
        try {

            $support = new B2BSupport();
            $dataArray = $request->only($support->getFillable());
            $upload_id = Upload::uploadFile($request->image, 200, 200, 'base64Image', 'png', true);

            B2BSupport::createSupport($dataArray, $upload_id);

            return Helpers::successResponse('Support Created successfully.');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function AllIntentions()
    {
        try {

            $data = B2BIntentionOption::allIntentions();

            return Helpers::successResponse('All Intentions', $data);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }

    public function b2bAccountCheck(Request $request)
    {
        try {

            if (!empty($request['email'])) {

                $data = User::checkB2BEmail($request['email']);

                if (!empty($data)) {

                    return Helpers::successResponse('B2B Already Have Account ', [
                        'b2b_signup_step' => $data['b2b_step'],
                        'existing_account' => true,
                    ]);

                } else {

                    $uniqueEmail = UserInvite::where('email', $request['email'])->first();

                    if ($uniqueEmail) {

                        return Helpers::successResponse('Invite Link Have Please Create Acccount', [
                            'url' => config('client_url.b2b_dashboard_url') . '/register?b2b-signup-link=' . $uniqueEmail['link'],
                            'existing_account' => false,
                        ]);

                    } else {
                        return Helpers::validationResponse('You are not recognized. Please check the invite link or contact support.');
                    }

                }
            } else {

                if (!empty($request['invite_link'])) {

                    $data = UserInvite::getInviteLink($request['invite_link']);

                    if (!empty($data)) {

                        return Helpers::successResponse('signup Link for Maestro HumanOp', [
                            'url' => config('client_url.b2b_dashboard_url') . '/register?b2b-signup-link=' . $request['invite_link'],
                        ]);

                    } else {
                        return Helpers::validationResponse('You are not recognized. Please check the invite link or contact support.');
                    }

                } else {
                    return Helpers::validationResponse('Invite Link Is Required');
                }

            }


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }


    public function  b2bRegisterFirstStep(B2BRegisterfirstStep  $request)
    {
        try {
            $data=$request->only(['email']);
           $data= User::updateWorkEmail($request['user_id'],$request['email']);
           
           if($data){
               return  Helpers::successResponse('Work email stored succefully',[

               ]);
           }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

}
