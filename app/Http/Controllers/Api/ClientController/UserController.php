<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\ChangePasswordRequest;
use App\Http\Requests\Api\Client\Feedback\StoreUserFeedback;
use App\Http\Requests\Api\Client\UpdateUserProfileRequest;
use App\Http\Requests\Api\Client\User\GoogleLoginSignupRequest;
use App\Models\Client\Feedback\Feedback;
use App\Models\Upload\Upload;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

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

            if ($request->profile_image){

                $upload_id = Upload::uploadFile($request->profile_image, 200, 200, 'base64Image','png', true);
                $request->merge(['image_id' => $upload_id]);
                $dataArray = $request->only(['first_name','last_name','phone','age_max','age_min','gender','image_id']);

            }else{
                $dataArray = $request->only(['first_name','last_name','phone','age_max','age_min','gender']);

            }

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

    public function deleteProfile(){

        try {

            User::whereId(Helpers::getUser()->id)->delete();

            Session::flush();

            return Helpers::successResponse('User deleted successfully');

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function userFeedback(StoreUserFeedback $request){

        try {

            $feedback = new Feedback();

            $dataArray = $request->only($feedback->getFillable());

            $dataArray['user_id'] = Helpers::getUser()->id;

            Feedback::storeClientFeedback($dataArray);

            return Helpers::successResponse('Your feedback successfully submitted');

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function googleLoginSignup(GoogleLoginSignupRequest $request){

        try {

            $user = Socialite::driver('google')->userFromToken($request->input('google_access_token'));

            if ($user){

                $finduser = User::where('google_id', $user->id)->orWhere('email', $user->email)->first();

                if($finduser){

                    $token = Auth::guard('api')->login($finduser);

                    $user_data = User::user($finduser->id);

                    $data = [

                        'user' => $user_data,

                        'authorization' => [

                            'token' => $token,

                            'type' => 'bearer',
                        ]
                    ];

                    $message = "LoggedIn successfully";

                }else{

                    $newUser = User::create([
                        'email' => $user->email,
                        'first_name' => $user->user['given_name'] ?? "",
                        'last_name' => $user->user['family_name'] ?? "",
                        'google_id'=> $user->id,
                        'password' => $user->id,
                        'is_admin' => 2,
                        'password_set' => 2,
                    ]);

                    $token = Auth::guard('api')->login($newUser);

                    $user_data = User::user($newUser->id);

                    $data = [

                        'user' => $user_data,

                        'authorization' => [

                            'token' => $token,

                            'type' => 'bearer',
                        ]
                    ];

                    $message = "Signup successfully";

                }

                return Helpers::successResponse($message, $data);

            }else{

                return Helpers::validationResponse('User not found on google');
            }

        }catch(\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }
}
