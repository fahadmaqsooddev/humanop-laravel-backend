<?php

namespace App\Http\Controllers\Api\ClientController;

use Carbon\Carbon;
use App\Models\User;
use App\Helpers\Helpers;
use App\Enums\Admin\Admin;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\Points\PointHelper;
use App\Models\AssessmentColorCode;
use App\Events\DailyTip\NewDailyTip;
use App\Http\Controllers\Controller;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Admin\Podcast\Podcast;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Admin\DailyTip\UserDailyTip;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\Information\InformationIcon;
use App\Models\Admin\Notification\Notification;
use App\Models\Admin\AssessmentWalkthrough\AssessmentWalkThrough;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function dailyTip()
    {
        try {
            $user = Helpers::getWebUser() ?? Helpers::getUser();

            $assessment = Assessment::getLatestAssessment($user['id']);

            if (!empty($assessment))
            {
                $userDailyTip = UserDailyTip::getLatestTip();

                if ($userDailyTip) {

                    $isRead = $userDailyTip['is_read'];

                    $updatedWithinDay = $userDailyTip['updated_at'] >= now()->subDay();

                    if ($isRead == 0 || ($isRead == 1 && $updatedWithinDay)) {

                        $data = [
                            'title' => $userDailyTip['dailyTip']['title'] ?? '',
                            'description' => $userDailyTip['dailyTip']['description'] ?? '',
                            'is_read' => $isRead,
                            'created_at' => $isRead == 1 ? $userDailyTip['updated_at'] : null,
                        ];

                        return Helpers::successResponse('Daily Tip', $data);
                    }
                }

                do {

                    $randomCode = DailyTip::randomCode($assessment);

                    $newDailyTip = DailyTip::getSameCodeTips($randomCode);

                    if ($newDailyTip) {

                        $latestTip = UserDailyTip::where('user_id', $user['id'])
                            ->where('daily_tip_id', $newDailyTip['id'])
                            ->latest()
                            ->first();

                        if (empty($latestTip)) {

                            $newUserDailyTip = UserDailyTip::createUserDailyTip($user['id'], $newDailyTip['id'], $assessment['id']);

                            $message = 'Your New Daily Tip';

                            event(new NewDailyTip($user['id'], 'new daily tip', $message));

                            Helpers::OneSignalApiUsed($user['id'], 'new daily tip', $message);

                            Notification::createNotification('Daily Tip', $message, $user['device_token'], $user['id'], 1, Admin::DAILY_TIP_NOTIFICATION);

                            $data = [
                                'title' => $newUserDailyTip['dailyTip']['title'],
                                'description' => $newUserDailyTip['dailyTip']['description'],
                                'is_read' => $newUserDailyTip['is_read'],
                                'created_at' => $newUserDailyTip['is_read'] == 1 ? $newUserDailyTip['updated_at'] : null,
                            ];

                            return Helpers::successResponse('Daily Tip', $data);
                        }
                    }
                } while ($newDailyTip && $latestTip && $latestTip['is_read'] == 1 && $latestTip['updated_at'] >= now()->subYear());

                return Helpers::validationResponse('No new daily tip found.');

            }
            else
            {
                return Helpers::successResponse('No new daily tip found.');

            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function haiChatStatus()
    {
        try {
            $user_id = Helpers::getWebUser()->id ?? Helpers::getUser()->id;
            $haiStatus = User::checkHaiStatus($user_id);

            if ($haiStatus) {
                $data = [
                    'status' => $haiStatus
                ];
            } else {
                $data = [
                    'status' => false
                ];
            }
            return Helpers::successResponse('HAI CHAT status fetched successfully', $data);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function latestPodcast()
    {

        try {

            $podcast = Podcast::getPodcast();

            return Helpers::successResponse('Podcast url', $podcast);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function coreStats(Request $request)
    {

        try {

            $assessment = Assessment::singleAssessmentFromId($request->input('assessment_id', null));

            $coreState = Assessment::getCoreState($assessment, Helpers::getUser()->date_of_birth);

            return Helpers::successResponse('core stats', $coreState);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function dailyTipRead()
    {

        try {

            DB::beginTransaction();

            $daily_tip_updated = UserDailyTip::readUserDailyTip();

            if (!$daily_tip_updated) {

                $point = PointHelper::addPointsOnDailyTipRead();
            }

            DB::commit();

            return Helpers::successResponse('Daily tip read', ['point' => $point ?? 0]);

        } catch (\Exception $exception) {

            DB::rollBack();

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function actionPlan(Request $request)
    {

        try {

            if (!empty($request['user_id']))
            {
                $userId = $request['user_id'];
            }
            else
            {
                $userId = Helpers::getUser()['id'];
            }

            $assessment = Assessment::getLatestAssessment($userId);

            ActionPlan::checkUserActionPlan($assessment);

            $plan = ActionPlan::userActionPlan();

            return Helpers::successResponse('Action plan', $plan);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function informationIcon()
    {

        try {

            $info = InformationIcon::getInfo();

            return Helpers::successResponse('Information Icons', $info);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function optionalTrait()
    {
        try {

            $user = Helpers::getUser();

            $assessment = Assessment::getLatestAssessment($user['id']);

            if (!empty($assessment)) {

                $timezone = $user['timezone'];

                $topThreeStyles = Assessment::getAllStyles($assessment);

                $topFeatures = Assessment::getFeatures($assessment);

                $topTwoFeatures = Assessment::getTopTwoFeatures($topFeatures['top_two_keys'], $assessment);

                $optionalTrait = Helpers::getOptionalTrait($timezone, $topThreeStyles, $topTwoFeatures);

                $optionalTraitDetail = CodeDetail::getOptionalTraitDetail($optionalTrait);

                return Helpers::successResponse('optional trait', $optionalTraitDetail);

            } else {
                return Helpers::notFoundResponse('Assessment not found');
            }


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }


    public function getWalkThrough(){
        try {

            $user = Helpers::getUser();

            $assessment = Assessment::getLatestAssessment($user['id']);
            $coreState = Assessment::getCoreState($assessment ?? '', $user['date_of_birth'] ?? '');
  



            // $data = [];

        //     foreach ($coreState as $asses) {
        //     if (is_array($asses) && count($asses)) {
        //     foreach ($asses as $nested) {
        //     if (isset($nested['code_name'])) {
        //         // $data[] = AssessmentWalkThrough::getbyCodeName($nested['code_name']);
        //         $data[] = AssessmentWalkThrough::getbyCodeName($nested['code_name']);
               
                
        //     }
        //     }
        //     } elseif (isset($asses['code_name'])) {
        
        // $data[] = AssessmentWalkThrough::getbyCodeName($nested['code_name']);

        //     }
        //     }

        //     return Helpers::successResponse('optional trait', $data);

        


            
       
       
        $data = [
            'largest-trait' => [],
            'second-trait' => [],
            'third-trait' => [],
            'pilot-trait' => [],
            'co-pilot-trait' => [],
            'alchemy-trait' => [],
            'communication-trait' => [],
            'polarity-trait' => [],
            
        ];
        
        foreach ($coreState as $asses) {
            if (is_array($asses) && count($asses)) {
                foreach ($asses as $nested) {
                    if (isset($nested['code_name'])) {
                        $results = AssessmentWalkThrough::getbyCodeName($nested['code_name']);
                        
                        foreach ($results as $item) {
                            switch ($item->title) { // Convert to string for safety
                                case "1":
                                    $item['title']='largest-trait';
                                    $data['largest-trait'][] = $item;
                                    break;
                                case "2":
                                    $data['second-trait'][] = $item;
                                    break;
                                case "3":
                                    $data['third-trait'][] = $item;
                                    break;
                                case "4":
                                    $data['pilot-trait'][] = $item;
                                    break;
                                case "5":
                                    $data['co-pilot-trait'][] = $item;
                                    break;
                                case "6":
                                    $data['alchemy-trait'][] = $item;
                                    break;
                                case "7":
                                    $data['communication-trait'][] = $item;
                                    break;
                                case "8":
                                    $data['polarity-trait'][] = $item;
                                    break;
                                default:
                                    $data[] = $item;
                                    break;
                            }
                        }
                    }
                }
            } elseif (isset($asses['code_name'])) {
                $results = AssessmentWalkThrough::getbyCodeName($asses['code_name']);
                
                foreach ($results as $item) {
                    switch ($item->title) {
                        case "1":
                            $data['largest-trait'][] = $item;
                            break;
                        case "2":
                            $data['second-trait'][] = $item;
                            break;
                        case "3":
                            $data['third-trait'][] = $item;
                            break;
                        case "4":
                                $data['pilot-trait'][] = $item;
                                break;
                        case "5":
                                $data['co-pilot-trait'][] = $item;
                                break;
                        case "6":
                                $data['alchemy-trait'][] = $item;
                                break;
                        case "7":
                                $data['communication-trait'][] = $item;
                                break;
                        case "8":
                                $data['polarity-trait'][] = $item;
                                break;    
                        default:
                            $data[] = $item;
                            break;
                    }
                }
            }
        }


        
        
      
        return Helpers::successResponse('optional trait', $data);
        





         


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }
}
