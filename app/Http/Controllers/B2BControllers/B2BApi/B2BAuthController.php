<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\LoginRequest;
use App\Models\User;
use App\Models\UserInvite\UserInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\B2B\RegisterRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;


class B2BAuthController extends Controller
{

    protected $auth;

    public function __construct()
    {
        $this->middleware('auth:api')->except(['loginB2b','registerStep']);

        $this->auth = Auth::guard('api');
    }


    public function registerStep(RegisterRequest $request){
      

        try {

            $user = new User();

            $dataArray = $request->only($user->getFillable());

            $parts = explode(' ', $request->input('full_name'));

            $dataArray['first_name'] = $parts[0] ?? '';

            $dataArray['last_name'] = $parts[1] ?? '';
            

            $authorizedUser = UserInvite::getSingleInvite($dataArray['email']);

            if (!empty($authorizedUser)) {

                $checkDeleteAccount = $user->checkDeleteEmail($dataArray['email']);

                if (!empty($checkDeleteAccount)) {
                    return Helpers::validationResponse('Your account associated with this email has been frozen. Please contact our technical support team for assistance.');
                }
                $checkUser = $user->checkEmail($dataArray['email']);
                
                if (empty($checkUser)) {
                    
                    $user = $user->createB2BSignup($dataArray, $request['google_id'], $request['apple_id']);                               
                   
                    
                    Helpers::createClientsOnOneSignal($user->id);

                
                    return Helpers::successResponse('User registered successfully', [
                        'authorization' => [
                            // 'user' => $user,
                            'status' => true,
                            'type' => 'bearer',
                        ],
                    ]);

                }

                
            } else {

                return Helpers::validationResponse('You are not recognized. Please check the invite link or contact support.');
            }

        } catch (\Exception $exception) {

           

            // return Helpers::serverErrorResponse($exception->getMessage());

            
                \Log::error('Exception in registerStep:', [
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTraceAsString(),
                ]);
            
                return Helpers::serverErrorResponse($exception->getMessage());
           
            

        }
    }




   
}
