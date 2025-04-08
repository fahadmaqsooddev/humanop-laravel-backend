<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Enums\Admin\Admin;
use App\Helpers\BlueHelper\BlueHelpers;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\ChangePasswordRequest;
use App\Http\Requests\Api\Client\TwoWayAuthRequest;
use App\Http\Requests\Api\Client\ChangeTimezoneRequest;
use App\Http\Requests\Api\Client\Feedback\StoreUserFeedback;
use App\Http\Requests\Api\Client\updateIntentionPlanRequest;
use App\Http\Requests\Api\Client\UpdateUserProfileRequest;
use App\Http\Requests\Api\Client\UpdateUserImageRequest;
use App\Http\Requests\Api\Client\User\GoogleLoginSignupRequest;
use App\Http\Requests\Client\Register\ResetPasswordRequest;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Admin\VersionControl\Version;
use App\Models\Assessment;
use App\Models\AssessmentColorCode;
use App\Models\Client\Feedback\Feedback;
use App\Models\GenerateFile\PdfGenerate;
use App\Models\IntentionPlan\IntentionOption;
use App\Models\IntentionPlan\IntentionPlan;
use App\Models\Upload\Upload;
use App\Models\User;
use App\Models\UserInvite\UserInvite;
use Carbon\Carbon;
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
        $this->middleware('auth:api')->except(['googleLoginSignup', 'intentionOption', 'getLatestVersion', 'getTimezone', 'forgotPassword']);

        $this->user = $user;
    }


    public function userProfile()
    {

        try {

            $user = User::user(Helpers::getUser()->id);

            return Helpers::successResponse('User information', $user);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function changeTwoWayAuth(TwoWayAuthRequest $request)
    {
        try {
            $status = $request->status;

            if ($status == 1) {
                User::updateUser(['two_way_auth' => 1], Helpers::getUser()->id);
            } else {
                User::updateUser(['two_way_auth' => 2], Helpers::getUser()->id);
            }
            return Helpers::successResponse('2 Way Auth successfully updated');
        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function completeIntro(Request $request)
    {
        try {


            User::updateUser(['app_intro_check' => 1], Helpers::getUser()->id);

            return Helpers::successResponse('Intro Completed Successfully');

        } catch (\Exception $exception) {


            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function updateUserProfile(UpdateUserProfileRequest $request)

    {


        try {

            $request = Helpers::explodeAgeRangeIntoAge($request);

            // if ($request->profile_image) {

            //     $upload_id = Upload::uploadFile($request->profile_image, 200, 200, 'base64Image', 'png', true);
            //     $request->merge(['image_id' => $upload_id]);
            //     $dataArray = $request->only(['first_name', 'last_name', 'phone', 'age_max', 'age_min', 'gender', 'image_id','timezone']);

            // } else {
            //     $dataArray = $request->only(['first_name', 'last_name', 'phone', 'date_of_birth', 'gender','timezone']);

            // }
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

    public function updateUserImage(UpdateUserImageRequest $request)
    {
        try {
            if ($request->profile_image) {
                $upload_id = Upload::uploadFile($request->profile_image, 200, 200, 'base64Image', 'png', true);
                $user = Helpers::getUser();
                $updated_user = $user->update(['image_id' => $upload_id]);
                tap($user);
                return Helpers::successResponse('User updated successfully', $user);
            } else {
                return Helpers::forbiddenResponse('Please Select Image');
            }

        } catch (\Exception $exception) {
            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }


    public function changePassword(ChangePasswordRequest $request)
    {

        try {

            if (Hash::check($request->input('current_password'), Helpers::getUser()->password)) {

                if (!Hash::check($request->input('new_password'), Helpers::getUser()->password)) {

                    User::updateUserPassword($request->input('new_password'));

                    return Helpers::successResponse('Password successfully updated');

                } else {

                    return Helpers::validationResponse('The current and new passwords cannot be the same.');
                }

            } else {

                return Helpers::validationResponse('Current Password is incorrect');
            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }


    public function forgotPassword(ResetPasswordRequest $request)
    {

        $dataArray = $request->only($this->user->getFillable());

        $token = $request['token'];

        $user = User::where('reset_password_token', $token)->first();

        if (!empty($token) && !empty($user)) {

            $user->password = $dataArray['password'];

            $user->reset_password = 1;

            $user->save();

            Auth::logoutOtherDevices($user->password);

            return Helpers::successResponse('Your password has been reset');

        } else {

            return Helpers::forbiddenResponse('Reset password link not match');
        }


    }

    public function getTimezone()
    {

        try {

            $timezones = Helpers::timeZone();

            return Helpers::successResponse('Timezone successfully updated', $timezones);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function updateUserTimezone(ChangeTimezoneRequest $request)
    {

        try {
            $user = Helpers::getUser();

            if ($user) {

                $timezones = $user->update([

                    'timezone' => $request['timezone']

                ]);

                return Helpers::successResponse('Timezone successfully updated');
            } else {
                return Helpers::forbiddenResponse('User Does Not Foiund');
            }


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function getLatestVersion()
    {

        try {

            $version = Version::getLatestVersion();


            return Helpers::successResponse('Get Latest Version', $version);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function deleteProfile()
    {

        try {

            UserInvite::deleteInvite();
            User::whereId(Helpers::getUser()->id)->delete();

            Session::flush();

            return Helpers::successResponse('User deleted successfully');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function userFeedback(StoreUserFeedback $request)
    {

        try {

            $feedback = new Feedback();

            $dataArray = $request->only($feedback->getFillable());

            $dataArray['user_id'] = Helpers::getUser()->id;

            if(!empty($request->hasfile('image'))){

                $upload_id = Upload::uploadFile($request->image, 200, 200, 'base64Image', 'png', true);

                $dataArray['image_id']=$upload_id;
            }

             $result= Feedback::storeClientFeedback($dataArray);

            if(!empty($result['image_id'])){

                $url=Helpers::getImage($result['image_id']);

            }
            else{

                $url=''; 
            }
        
              
            // $response = BlueHelpers::createBlueRecord($request['title'], $request['comment'], $request['platform'], Helpers::getUser()['email']);
            $response = BlueHelpers::createBlueRecord($request['title'], $request['comment'], $request['platform'], Helpers::getUser()['email'],$url);

//            if (isset($response['errors'])) {
//                dd($response['errors']); // Debugging errors
//            } else {
//                dd($response['data']['createTodo']); // Output the created record
//            }

            return Helpers::successResponse('Thank you for your feedback! We have given you a point as a token of our appreciation!');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function googleLoginSignup(GoogleLoginSignupRequest $request)
    {

        try {

            $user = Socialite::driver('google')->userFromToken($request->input('google_access_token'));

            if ($user) {

                $finduser = User::where('google_id', $user->id)->orWhere('email', $user->email)->first();

                if ($finduser) {

                    $token = Auth::guard('api')->login($finduser);

                    $user_data = User::user($finduser->id);

                    $user = Helpers::getUser();

                    Helpers::createCustomerAndSubscriptionOnStripe($user);

//                    DailyTip::updateUserDailyTip();

//                    ActionPlan::storeUserActionPlan();


                    $data = [

                        'user' => $user_data,

                        'authorization' => [

                            'token' => $token,

                            'type' => 'bearer',
                        ]
                    ];
                    User::updateUserIsFeedback();
                    $message = "LoggedIn successfully";

                } else {

                    $newUser = User::create([
                        'email' => $user->email,
                        'first_name' => $user->user['given_name'] ?? "",
                        'last_name' => $user->user['family_name'] ?? "",
                        'google_id' => $user->id,
                        'password' => $user->id,
                        'is_admin' => 2,
                        'password_set' => 2,
                        'status' => 1,
                    ]);

                    $token = Auth::guard('api')->login($newUser);

                    $user_data = User::user($newUser->id);

                    $user = Helpers::getUser();

                    Helpers::createCustomerAndSubscriptionOnStripe($user);

//                    DailyTip::updateUserDailyTip();

//                    ActionPlan::storeUserActionPlan();

                    $data = [

                        'user' => $user_data,

                        'authorization' => [

                            'token' => $token,

                            'type' => 'bearer',
                        ]
                    ];
                    User::updateUserIsFeedback();
                    $message = "Signup successfully";

                }

                return Helpers::successResponse($message, $data);

            } else {

                return Helpers::validationResponse('User not found on google');
            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function updateintentionPlan(updateIntentionPlanRequest $request)
    {
        try {

            $user = Helpers::getUser();

            IntentionPlan::where('user_id', $user['id'])->delete();

            IntentionPlan::updateIntentionPlan($user['id'], $request->ninety_day_intention);

            return Helpers::successResponse('updated successfully.', $request->ninety_day_intention);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }


    }

    public function intentionOption()
    {
        try {

            $intention_option = IntentionOption::getOptions();

            return Helpers::successResponse('success', $intention_option);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function profileOverviewResult(Request $request)
    {

        try {


            $user_age = Carbon::parse(Helpers::getUser()->date_of_birth)->age;

            $assessment = Assessment::singleAssessmentFromId($request->input('assessment_id', null));

            if(empty($assessment)){
                return Helpers::validationResponse('Assessment Not Found');
            }

            $user_name =Helpers::getUser()->first_name.' ' .Helpers::getUser()->last_name;
            $gender =Helpers::getUser()->gender == 0 ?'(M)':'(F)';

            $allStyles = $assessment != null ? Assessment::getAllStyles($assessment) : [];

            $topFeatures = $assessment != null ? Assessment::getFeatures($assessment) : [];

            $topTwoFeatures = $topFeatures != null ? Assessment::getTopTwoFeatures($topFeatures['top_two_keys'], $assessment) : [];

            $boundary = $assessment != null ? Assessment::getAlchemyDetail($assessment) : [];

            $communication = $assessment != null ? Assessment::getEnergy($assessment) : null;

            $perception_life = CodeDetail::getPerceptionStaticText();

            $perception = $assessment != null ? Assessment::getPreceptionReportDetail($assessment) : null;

            $topCommunication = $communication != null ? CodeDetail::getCommunicationDetail($communication, $assessment) : [];

            $energyPool = $assessment != null ? Assessment::getEnergyPoolPublicName($assessment) : null;

            $summary_static = CodeDetail::summaryIntro();
            $main_result = CodeDetail::mainResult();
            $cycle_life = CodeDetail::cycleLife();
            $trait_intro = CodeDetail::traitIntro();
            $motivation_intro = CodeDetail::motivationIntroduction();
            $intro_boundaries = CodeDetail::introBoundaries();
            $intro_communication = CodeDetail::introCommunication();
            $intro_energypool = CodeDetail::introEnergypool();
            $intro_perceptionlife = CodeDetail::perceptionLife();

            $style_position = AssessmentColorCode::getStylePosition($assessment->id);
        $feature_position = AssessmentColorCode::getFeaturePosition($assessment->id);
        $positive = $assessment['sa'] + $assessment['jo'] + $assessment['ven'] + $assessment['so'];
        $negative = $assessment['ma'] + $assessment['lu'] + $assessment['mer'];

        $ep = $positive + $negative;
        $pv = $positive - $negative;

            $data = [
                'user_name'=>$user_name,
                'user_age' => $user_age,
                'gender'=>$gender,
                'summary_intro'=>$summary_static,
                'main_result_into'=>$main_result,
                'intro_cycle_life'=>$cycle_life,
                'traits_intro'=>$trait_intro,
                'all_styles' => $allStyles,
                'motivation_introduction'=>$motivation_intro,
                'top_features' => $topTwoFeatures,
                'intro_boundaries'=>$intro_boundaries,
                'boundary' => $boundary,
                'intro_perception' => $perception_life,
                'perception' => $perception,
                'intro_communication'=>$intro_communication,
                'top_communication' => $topCommunication,
                'intro_energypool'=>$intro_energypool,
                'energy_pool' => $energyPool,
                'style_position' => $style_position,
                'feature_position' => $feature_position,
                'positive' => $positive,
                'negative' => $negative,
                'pv' => $pv,
                'ep' => $ep,
                'footer'=> config('pdffooter'),
                'completed_date' => Carbon::parse($assessment['updated_at'])->format('F j, Y')
            ];

            return Helpers::successResponse('Profile overview data', $data);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function summaryReport(Request $request)
    {

        try {

            $user_name = Helpers::getUser()->first_name . ' ' . Helpers::getUser()->last_name;
            $assessment = Assessment::singleAssessmentFromId($request->input('assessment_id', null));
            $Styles = $assessment != null ? Assessment::getAllStyles($assessment) : [];
            $topFeatures = $assessment != null ? Assessment::getFeatures($assessment) : [];
            $topTwoFeatures = $topFeatures != null ? Assessment::getTopTwoFeatures($topFeatures['top_two_keys'], $assessment) : [];
            $boundary = $assessment != null ? Assessment::getAlchemyDetail($assessment) : null;
            $communication = $assessment != null ? Assessment::getEnergy($assessment) : null;
            $perception = $assessment != null ? Assessment::getPreceptionReportDetail($assessment) : null;
            $topCommunication = $communication != null ? CodeDetail::getCommunicationDetail($communication) : [];
            $energyPool = $assessment != null ? Assessment::getEnergyPoolDetail($assessment) : null;
            $alchl_code = $assessment != null ? Assessment::getAlchlCode($assessment['id']) : null;
            $style_position = $assessment != null ? AssessmentColorCode::getStylePosition($assessment['id']) : null;
            $feature_position = $assessment != null ? AssessmentColorCode::getFeaturePosition($assessment['id']) : null;
            $negative = $assessment != null ? $assessment['ma'] + $assessment['lu'] + $assessment['mer'] : null;
            $positive = $assessment != null ? $assessment['sa'] + $assessment['jo'] + $assessment['ven'] + $assessment['so'] : null;
            $ep = $assessment != null ? $positive + $negative : null;
            $pv = $assessment != null ? $positive - $negative : null;

            if ($assessment) {

                $allStyles = PdfGenerate::createGenerateFile($assessment['id'], Helpers::getUser()->id, $Styles);
            }


            $data = [
                'user_name' => $user_name,
                'user_gender' => Helpers::getUser()->gender === 0 ? Admin::IS_MALE : (Helpers::getUser()->gender === 1 ? Admin::IS_FEMALE : ''),
                'top_two_feature' => $topTwoFeatures,
                'boundary' => $boundary,
                'perception' => $perception,
                'top_communication' => $topCommunication,
                'energy_pool' => $energyPool,
                'all_styles' => $allStyles ?? [],
                'style_position' => $style_position,
                'feature_position' => $feature_position,
                'alchemy_code' => $alchl_code,
                'ep' => $ep,
                'pv' => $pv
            ];

            return Helpers::successResponse('Summary Report', $data);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }
}
