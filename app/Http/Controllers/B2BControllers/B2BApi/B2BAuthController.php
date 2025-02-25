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

                    return Helpers::successResponse('User registered successfully', [
                        'authorization' => [
                             'user' => $b2b_user,
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


    // public function addMember(AddMemberRequest $request){
    //     try{

    //         $user = new User();

    //         $dataArray = $request->only($user->getFillable());

    //         // $authorizedUser = UserInvite::getSingleInvite($dataArray['email']);
    //         $checkeemail = $user->checkEmail($dataArray['email']);
    //         dd($checkeemail);
    //         if(!empty($checkeemail)){
    //             $checkDeleteAccount = $user->checkDeleteEmail($dataArray['email']);
    //              dd($checkDeleteAccount);
    //             if (!empty($checkDeleteAccount)) {
    //                 return Helpers::validationResponse('Your account associated with this email has been frozen. Please contact our technical support team for assistance.');
    //             }elseif($checkeemail['business_id'] != null){
    //                 return Helpers::validationResponse('This email is already associated with a business');
    //             } else {
    //                 $checkeemail->update(['business_id' => $request['business_id']]);
    //                 return Helpers::successResponse('This email is not associated with any business, we add it with you');
    //             }
    //         }else{
    //             $user=$user->addB2BMember($dataArray);
    //             Helpers::createClientsOnOneSignal($user->id);
                
    //             return Helpers::successResponse('User registered successfully', [
    //                 'authorization' => [
    //                     // 'user' => $user,
    //                     'status' => true,
    //                     'type' => 'bearer',
    //                     ],
    //                 ]);
    //         }


    //     }catch (\Exception $exception) {

    //         return Helpers::serverErrorResponse($exception->getMessage());


    //     }
    // }


    public function addMember(AddMemberRequest $request)
    {
        try {
            // Get only fillable fields from the request
            $dataArray = $request->only((new User())->getFillable());
    
            // Check if user already exists (including soft deleted users)
            $checkEmail = User::withTrashed()->where('email', $dataArray['email'])->first();
    
            if ($checkEmail) {
                // If the account is soft deleted, return the "frozen" message
                if ($checkEmail->trashed()) {
                    return Helpers::validationResponse('Your account associated with this email has been frozen. Please contact our technical support team for assistance.');
                }
    
                // Check if already associated with a business
                if ($checkEmail->business_id) {
                    return Helpers::validationResponse('This email is already associated with a business.');
                }
    
                // Associate the user with a business
                $checkEmail->update(['business_id' => $request->business_id]);
    
                return Helpers::successResponse('User successfully linked to your business.');
            }
    
            // Register a new user (only if email doesn't exist at all)
            $user = User::create($dataArray);
            Helpers::createClientsOnOneSignal($user->id);
    
            return Helpers::successResponse('User Linked successfully With Your Business.', [
                'authorization' => [
                    'status' => true,
                    'type' => 'bearer',
                ],
            ]);
        } catch (\Exception $exception) {
            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }
    



}
