<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\ChangePasswordRequest;
use App\Http\Requests\Api\Client\UpdateUserProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth:api');

        $this->user = $user;
    }


    public function userProfile(){

        try {

            $user = User::user(Helpers::getUser()->id);

            return Helpers::successResponse('User information', $user);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function updateUserProfile(UpdateUserProfileRequest $request){

        try {

            $request = Helpers::explodeAgeRangeIntoAge($request);

            $dataArray = $request->only(['first_name','last_name','phone','max_age','min_age','gender']);

            $updated_user = User::updateUserProfile($dataArray);

            return Helpers::successResponse('User updated successfully', $updated_user);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function changePassword(ChangePasswordRequest $request){

        try {

            if (Hash::check($request->input('current_password'), Helpers::getUser()->password)){

                if (!Hash::check($request->input('new_password'), Helpers::getUser()->password)){

                    User::updateUserPassword($request->input('new_password'));

                    return Helpers::successResponse('Password successfully updated');

                }else{

                    return Helpers::validationResponse('The current and new passwords cannot be the same.');
                }

            }else{

                return Helpers::validationResponse('Current Password is incorrect');
            }

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }
}
