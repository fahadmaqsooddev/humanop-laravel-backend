<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\HaiChat\HaiChatHelpers;
use App\Http\Requests\Api\Client\ShareDataRequest;
use App\Http\Requests\B2B\CandidatetoMember;
use App\Models\Admin\Alchemy\AlchemyCode;
use App\Models\B2B\B2BBusinessCandidates;
use App\Models\Client\HumanOpPoints\HumanOpPoints;
use App\Models\Notification\PushNotification;
use App\Models\UserOptimalTrait;
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
use App\Models\Admin\Resources\LibraryResource;
use App\Models\Admin\VersionControl\Version;

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

            if (!empty($assessment)) {

                $userDailyTip = UserDailyTip::getLatestTip();

                if ($userDailyTip) {

                    $isRead = $userDailyTip['is_read'];

                    $updatedWithinDay = $userDailyTip['updated_at'] >= now()->subDay();

                    if ($isRead == 0 || ($isRead == 1 && $updatedWithinDay)) {

                        HaiChatHelpers::syncUserRecordWithHAi();

                        $data = [
                            'daily_tip_id' => $userDailyTip['daily_tip_id'],
                            'title' => $userDailyTip['dailyTip']['title'] ?? '',
                            'description' => $userDailyTip['dailyTip']['description'] ?? '',
                            'is_read' => $isRead,
                            'favorite_daily_tip' => $userDailyTip['favorite_tip'],

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

                            HumanOpPoints::addPointsAfterCompleteDailyTip($user['id']);

                            $message = 'Your New Daily Tip';

                            event(new NewDailyTip($user['id'], 'new daily tip', $message));

                            Helpers::OneSignalApiUsed($user['id'], 'new daily tip', $message);

                            Notification::createNotification('Daily Tip', $message, $user['device_token'], $user['id'], 1, Admin::DAILY_TIP_NOTIFICATION, Admin::B2C_NOTIFICATION);

                            $data = [
                                'daily_tip_id' => $newUserDailyTip['daily_tip_id'],
                                'title' => $newUserDailyTip['dailyTip']['title'],
                                'description' => $newUserDailyTip['dailyTip']['description'],
                                'is_read' => $newUserDailyTip['is_read'],
                                'favorite_daily_tip' => $newUserDailyTip['favorite_tip'],
                                'created_at' => $newUserDailyTip['is_read'] == 1 ? $newUserDailyTip['updated_at'] : null,
                            ];

                            return Helpers::successResponse('Daily Tip', $data);
                        }
                    }
                } while ($newDailyTip && $latestTip && $latestTip['is_read'] == 1 && $latestTip['updated_at'] >= now()->subYear());

                return Helpers::validationResponse('No new daily tip found.');

            } else {

                return Helpers::successResponse('No new daily tip found.');

            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function favoriteDailyTip(Request $request)
    {
        try {

            $favoriteTip = UserDailyTip::userFavoriteDailyTip($request['daily_tip_id']);

            $message = 'Your daily tip has been ' . ($favoriteTip['favorite_tip'] == 2 ? 'favorited' : 'not favorited') . '.';

            return Helpers::successResponse($message);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function getFavoriteDailyTip()
    {
        try {
            $favoriteTips = UserDailyTip::getUserFavoriteDailyTip();

            $tips = [];

            foreach ($favoriteTips as $favoriteTip) {

                foreach ($favoriteTip['dailyTips'] as $dailyTip) {

                    $tips[] = [
                        'title' => $dailyTip['title'] ?? '',
                        'description' => $dailyTip['description'] ?? '',

                    ];
                };

            }

            return Helpers::successResponse('Your favorite daily tips', $tips);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function getHp()
    {
        try {

            $user = Helpers::getWebUser() ?? Helpers::getUser();

            $points = HumanOpPoints::getUserPoints($user);

            $hp['points'] = $points['points'];

            return Helpers::successResponse('Your HumanOp Points', $hp);

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

//        try {

            $assessment = Assessment::singleAssessmentFromId($request->input('assessment_id', null));

            $coreState = Assessment::getCoreState($assessment, Helpers::getUser()->date_of_birth);

            return Helpers::successResponse('core stats', $coreState);

//        } catch (\Exception $exception) {
//
//            return Helpers::serverErrorResponse($exception->getMessage());
//        }
    }

    public function dailyTipRead()
    {

        try {

            DB::beginTransaction();

            $daily_tip_updated = UserDailyTip::readUserDailyTip();

            if (!$daily_tip_updated) {

//                $point = PointHelper::addPointsOnDailyTipRead();
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

            if ($request->has('assessment_id')) {

                $assessment = Assessment::getSingleAssessment($request->input('assessment_id'));

            } else {

                $assessment = Assessment::getLatestAssessment(Helpers::getUser()['id']);

            }

            $actionPlan = ActionPlan::getActionPlanByAssessmentId($assessment['id']);

            if (empty($actionPlan)) {

                $actionPlan = ActionPlan::storeUserActionPlan($assessment);

            }

            return Helpers::successResponse('Action plan', $actionPlan);

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

    public function optimalTrait()
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

                $optionalTraitDetail = CodeDetail::getOptionalTraitDetail($optionalTrait['trait']);

                UserOptimalTrait::createUserOptimalTrait($optionalTraitDetail[0], $user['id'], $optionalTrait['status']);

                return Helpers::successResponse('optional trait', $optionalTraitDetail);

            } else {

                return Helpers::validationResponse('Assessment not found');

            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function getWalkThrough()
    {
        try {

            $assessment = Assessment::getLatestAssessment(Helpers::getUser()['id']);

            if (!empty($assessment)) {

                $getResult = AssessmentColorCode::getHighlightCodeColor($assessment['id']);

                $style = ['sa', 'ma', 'jo', 'lu', 'ven', 'mer', 'so'];

                $getStyle = [];

                foreach ($getResult as $key => $result) {

                    if (in_array($key, $style)) {
                        $getStyle[$key] = $result;
                    }

                }

                arsort($getStyle);

                $getTopStyles = array_slice($getStyle, 0, 3, true);

                $traits = [];

                $styleNumber = 1;

                foreach ($getTopStyles as $styleKey => $style) {

                    $traits[] = AssessmentWalkThrough::getbyCodeName(strtoupper($styleKey), $styleNumber);

                    $styleNumber += 1;
                }


                $features = ['de', 'dom', 'fe', 'gre', 'lun', 'nai', 'ne', 'pow', 'sp', 'tra', 'van', 'wil',];

                $getFeature = [];

                foreach ($getResult as $key => $result) {

                    if (in_array($key, $features)) {

                        $getFeature[$key] = $result;

                    }

                }

                arsort($getFeature);

                $drivers = [];

                $driverNumber = 4;

                foreach ($getFeature as $driverKey => $driver) {

                    $drivers[] = AssessmentWalkThrough::getbyCodeName(strtoupper($driverKey), $driverNumber);

                    $driverNumber += 1;
                }

                $gold = $assessment['g'];
                $silver = $assessment['s'];
                $copper = $assessment['c'];
                $alchemy = $gold . '' . $silver . '' . $copper;
                $alchemyCodeDetail = AlchemyCode::getCodeDeatil($alchemy);

                if (!empty($alchemyCodeDetail)) {

                    $alchemyBoundary = AssessmentWalkThrough::getbyCodeName($alchemyCodeDetail['code'], Admin::ALCHEMY_TRAIT);

                } else {

                    $alchemyBoundary = null;

                }

                $getCommunications = $assessment != null ? Assessment::getEnergy($assessment) : null;

                $communication = AssessmentWalkThrough::getbyCodeName($getCommunications[0], Admin::COMMUNICATION_TRAIT);

                $positive = $assessment['sa'] + $assessment['jo'] + $assessment['ven'] + $assessment['so'];

                $negative = $assessment['ma'] + $assessment['lu'] + $assessment['mer'];

                $pv = $positive - $negative;

                $ep = $positive + $negative;

                if ($pv <= -8) {

                    $polarity_code = 40;

                } elseif ($pv >= -7 and $pv <= 7) {

                    $polarity_code = 41;

                } elseif ($pv >= 8) {

                    $polarity_code = 42;

                }

                if ($ep < 25) {

                    $energy_code = 21;

                } elseif ($ep >= 25 and $ep <= 30) {

                    $energy_code = 18;

                } elseif ($ep >= 31 and $ep <= 35) {

                    $energy_code = 20;

                } elseif ($ep >= 36) {

                    $energy_code = 16;

                }

                $record = CodeDetail::whereId($polarity_code)->select(['id', 'code'])->first();

                $energyRecord = CodeDetail::whereId($energy_code)->select(['id', 'code'])->first();

                $polarity = AssessmentWalkThrough::getbyCodeName($record['code'], Admin::POLARITY_TRAIT);

                $energyPool = AssessmentWalkThrough::getbyCodeName($energyRecord['code'], Admin::ENERGY_POOL_TRAIT);

                $data = [
                    'trait' => $traits,
                    'driver' => $drivers,
                    'alchemy' => $alchemyBoundary,
                    'communication' => $communication,
                    'polarity' => $polarity,
                    'energyPool' => $energyPool
                ];
            } else {
                $data = [
                    'trait' => null,
                    'driver' => null,
                    'alchemy' => null,
                    'communication' => null,
                    'polarity' => null
                ];
            }

            return Helpers::successResponse('optional trait', $data);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function completeWalkThrough()
    {
        try {

            $user = User::completeAssessmentWalkthrought();

            if ($user['complete_assessment_walkthrough'] == 1) {

                return Helpers::successResponse('Assessment walkthrough completed');

            } else {

                return Helpers::validationResponse('Assessment walkthrough not completed');

            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function completeTutorial()
    {
        try {

            $user = User::completeTutorial();

            if ($user['complete_tutorial'] == 1) {

                return Helpers::successResponse('Tutorial completed');

            } else {

                return Helpers::validationResponse('Tutorial not completed');

            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function sharedData(ShareDataRequest $request)
    {
        try {

            $data = B2BBusinessCandidates::AllCompaniesCheckShareDataDetail($request['company_name'], $request['candidate_id']);

            if (!empty($data)) {

                foreach ($data as $shared) {

                    B2BBusinessCandidates::ShareDataWithBusiness($shared['business_id'], $request['candidate_id']);

                }

                return Helpers::successResponse('Data Shared Successfully');

            } else {

                return Helpers::validationResponse('Data not found.');

            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function CheckShareData(Request $request)
    {

        try {

            if (!empty($request['company_name'])) {

                $checkData = B2BBusinessCandidates::checkShareDataDetail($request['company_name']);

                if (!empty($checkData)) {

                    if ($checkData['share_data'] == Admin::NOT_SHARED_DATA) {

                        $data = [
                            'Shared_data' => Admin::NOT_SHARED_DATA,
                            'company_name' => $request['company_name'],
                            'status' => $checkData['role'] == Admin::IS_TEAM_MEMBER ? 'member' : 'candidate',
                        ];

                        return Helpers::successResponse('Check Shared Data', $data);
                    }

                    $data = [
                        'Shared_data' => Admin::SHARED_DATA,
                        'company_name' => $request['company_name']
                    ];

                    return Helpers::successResponse('Check Shared Data', $data);

                } else {

                    return Helpers::validationResponse('Data not found.');

                }

            } else {

                $companies = B2BBusinessCandidates::AllLoginUserCompanies();

                $data = [];

                foreach ($companies as $company) {

                    $data[] = [
                        'company_name' => $company->businessUsers->company_name ?? 'N/A',
                        'share_data' => $company->share_data ?? 'N/A'
                    ];
                }

                return Helpers::successResponse('All Share Data', $data);

            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function notSharedData(ShareDataRequest $request)
    {
        try {

            $userId = Helpers::getUser()['id'];

            if ($request['company_name']) {

                foreach ($request['company_name'] as $companyName) {

                    $company = User::getSingleUserFromCompanyName($companyName);

                    if (B2BBusinessCandidates::checkBusinessCandidate($company['id'], $userId)) {

                        B2BBusinessCandidates::notShareDataWithBusiness($company['id'], $userId);
                    }
                }
            }

            return Helpers::successResponse('Data Not Shared');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function pushNotification(Request $request)
    {
        try {

            $userId = Helpers::getUser()['id'];

            PushNotification::changeNotification($userId, $request['notification']);

            return Helpers::successResponse('Push Notification has been changed');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function getPushNotification(Request $request)
    {
        try {

            $userId = Helpers::getUser()['id'];

            $pushNotification = PushNotification::getSingleNotification($userId);

            return Helpers::successResponse('Push Notification', $pushNotification);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function getVersions()
    {
        try {
            $versions = Version::allVersions();

            $formattedVersions = [];

            foreach ($versions as $version) {

                $groupedDescriptions = $version->versionDescriptions->groupBy('version_heading');

                $newFeatureDescriptions = $groupedDescriptions->get(1, []);

                $issueFixedDescriptions = $groupedDescriptions->get(0, []);

                $newFeatureDescriptionsCleaned = [];

                $issueFixedDescriptionsCleaned = [];

                foreach ($newFeatureDescriptions as $description) {

                    $newFeatureDescriptionsCleaned[] = [
                        'description' => $description->description,
                        'platform' => $description->platform,
                    ];

                }

                foreach ($issueFixedDescriptions as $description) {

                    $issueFixedDescriptionsCleaned[] = [
                        'description' => $description->description,
                        'platform' => $description->platform,
                    ];

                }

                $formattedVersions[] = [

                    'version' => [
                        'Web_version' => $version->version,
                        'Ios_version' => $version->version,
                        'Android_version' => $version->version,
                        'note' => $version->note,
                        'created_at' => $version->created_at ? $version->created_at->format('F j, Y') : null,
                    ],
                    'new_feature_descriptions' => $newFeatureDescriptionsCleaned,
                    'issue_fixed_descriptions' => $issueFixedDescriptionsCleaned
                ];

            }

            return Helpers::successResponse('All Versions with Descriptions', $formattedVersions);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function versionUpdate()
    {

        try {

            $userId = Helpers::getUser()['id'];

            User::updateSingleUserVersion($userId);

            return Helpers::successResponse('Version Update Successfully');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function topLibraryResourcses()
    {
        try {

            $resources = LibraryResource::latestLibraryResourcses();

            $formatedResources = [];

            foreach ($resources as $resource) {

                $resource['created_at'] =

                $resource['resource_created_at'] = $resource['created_at'] ? Carbon::parse($resource['created_at'])->format('m/d/Y h:i A') : null;

                $formatedResources[] = $resource;
            }

            return Helpers::successResponse('Latest resources', $formatedResources);

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


    public static function allCompanies()
    {
        try {

            $companies = User::allCompanies();

            return Helpers::successResponse("All Companies Information", $companies);

        } catch (\Exception $exception) {
            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function checkFutureConsiderationShareData(CandidatetoMember $request)
    {

        try {

            $futureConsideration = B2BBusinessCandidates::checkFutureConsiderationShareData($request['candidate_id']);

            if (!empty($futureConsideration)) {

                $data = [
                    'company_name' => $futureConsideration['businessUsers']['company_name'],
                ];

                return Helpers::successResponse('Future Consideration', $data);
            }

            return Helpers::validationResponse('You are not Future Consideration');
        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

}
