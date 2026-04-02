<?php

namespace App\Http\Controllers\v4\Api\ClientController;

use App\Helpers\ActivityLogs\ActivityLogger;
use App\Helpers\HaiChat\HaiChatHelpers;
use App\Http\Requests\Api\Client\AddRecentPlayerRequest;
use App\Http\Requests\Api\Client\ShareDataRequest;
use App\Http\Requests\B2B\CandidatetoMember;
use App\Models\Admin\Alchemy\AlchemyCode;
use App\Models\Admin\AnnouncementNews\AnnouncementNews;
use App\Models\Admin\LifeTimeDeal\LifetimeDealBanner;
use App\Models\Admin\Plan\OptimizationPlan;
use App\Models\Admin\SuggestedItem\SuggestedItem;
use App\Models\B2B\B2BBusinessCandidates;
use App\Models\v4\Client\DailySync\DailySyncStreak;
use App\Models\v4\Client\HotSpot\HotSpotsPlan;
use App\Models\v4\Client\MultiMedia\MultiMediaStats;
use App\Models\v4\Client\Suggestion\SuggestionForYou;
use App\Models\Notification\PushNotification;
use App\Models\PlaylistLog;
use App\Models\UserOptimalTrait;
use Carbon\Carbon;
use App\Models\User;
use App\Helpers\Helpers;
use App\Enums\Admin\Admin;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AssessmentColorCode;
use App\Events\DailyTip\NewDailyTip;
use App\Http\Controllers\Controller;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Admin\Podcast\Podcast;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Admin\DailyTip\UserDailyTip;
use App\Models\v4\Client\Dashboard\ActionPlan;
use App\Models\Information\InformationIcon;
use App\Models\Admin\Notification\Notification;
use App\Models\Admin\AssessmentWalkthrough\AssessmentWalkThrough;
use App\Models\Admin\Resources\LibraryResource;
use App\Models\Admin\VersionControl\Version;
use App\Enums\UserActions\UserActions;
use App\Services\v4\UserActionService;

class DashboardController extends Controller
{
    public $user = null;

    const ASSESSMENT_DAYS = 90;

    const FREE_ASSESSMENT_DAYS = 14;

    public function __construct(User $user)
    {
        $this->middleware('auth:api');

        $this->user = Helpers::getUser();
    }

    public function changeThemeMode(Request $request)
    {

        $request->validate([
            'theme_mode' => 'required|in:dark,light'
        ]);

        $this->user->theme_mode = $request->theme_mode;

        $this->user->save();

        return Helpers::successResponse(
            'Theme mode updated successfully',
            $request->input('theme_mode')
        );
    }

    public function dailyTip()
    {
        try {

            $user = Helpers::getWebUser() ?? $this->user;

            $assessment = Assessment::getLatestAssessment($user['id']);

            if (!empty($assessment)) {

                $userDailyTip = UserDailyTip::getLatestTip();

                if ($userDailyTip) {

                    $isRead = $userDailyTip['is_read'];

                    if ($user['plan_name'] == 'Freemium') {

                        $updatedWithinDay = $userDailyTip['updated_at'] < now()->subDay();

                    } elseif ($user['plan_name'] == 'Premium' && !empty($user['set_daily_tip_time'])) {

                        $minutes = Helpers::explodeTimezoneWithHoursAndMinutes($user['timezone']);

                        $currentTime = Carbon::now()->addMinutes($minutes)->startOfMinute();

                        $setTipTimeToday = Carbon::parse($user['set_daily_tip_time'])->setDateFrom(Carbon::now())->startOfMinute();

                        $nextTipTime = $currentTime->greaterThan($setTipTimeToday) ? $setTipTimeToday->copy()->addDay() : $setTipTimeToday;

                        $updatedWithinDay = $currentTime->greaterThanOrEqualTo($nextTipTime);

                    } else {

                        $updatedWithinDay = $userDailyTip['updated_at'] < now()->subDay();

                    }

                    if ($isRead == 0 || ($isRead == 1 && $updatedWithinDay == false)) {

                        HaiChatHelpers::syncUserRecordWithHAi();

                        if ($user['plan_name'] == 'Freemium') {

                            $data = [
                                'daily_tip_id' => $userDailyTip['daily_tip_id'],
                                'title' => $userDailyTip['dailyTip']['title'] ?? '',
                                'description' => $userDailyTip['dailyTip']['description'] ?? '',
                                'is_read' => $isRead,
                                'favorite_daily_tip' => $userDailyTip['favorite_tip'],
                                'created_at' => $isRead == 1 ? $userDailyTip['tip_completed_at'] : null,
                            ];

                        } else {

                            $data = [
                                'daily_tip_id' => $userDailyTip['daily_tip_id'],
                                'title' => $userDailyTip['dailyTip']['title'] ?? '',
                                'description' => $userDailyTip['dailyTip']['description'] ?? '',
                                'is_read' => $isRead,
                                'favorite_daily_tip' => $userDailyTip['favorite_tip'],
                                'created_at' => $isRead == 1 ? $userDailyTip['tip_completed_at'] : null,
                                'nextTipTime' => !empty($nextTipTime) ? $nextTipTime->format('Y-m-d H:i:s.u T (P)') : null,
                                'currentTime' => !empty($currentTime) ? $currentTime->format('Y-m-d H:i:s.u T (P)') : null,
                                'check' => $updatedWithinDay ?? null
                            ];
                        }

                        return Helpers::successResponse('Daily Tip', $data);
                    }
                }

                do {

                    $randomCode = DailyTip::randomCode($assessment);

                    $newDailyTip = DailyTip::getSameCodeTips($randomCode);

                    if ($newDailyTip) {

                        $latestTip = UserDailyTip::where('user_id', $user['id'])->where('daily_tip_id', $newDailyTip['id'])->latest()->first();

                        if (empty($latestTip)) {

                            $getLatestTip = UserDailyTip::where('user_id', $user['id'])->latest()->first();

                            if (empty($getLatestTip)) {

                                UserDailyTip::createUserDailyTip($user['id'], $newDailyTip['id'], $assessment['id']);

                                $message = 'Your New Daily Tip';

                                event(new NewDailyTip($user['id'], 'new daily tip', $message));

                                Notification::createNotification('Daily Tip', $message, $user['device_token'], $user['id'], 1, Admin::DAILY_TIP_NOTIFICATION, Admin::B2C_NOTIFICATION, null, true);

                                UserActionService::dispatch($user['id'], UserActions::NEW_DAILY_TIP, ['message' => $message]);

                                ActivityLogger::addLog('new daily tip', "$message");

                                HaiChatHelpers::syncUserRecordWithHAi();

                            } elseif ($getLatestTip['updated_at']->startOfMinute() != Carbon::now()->startOfMinute()) {

                                UserDailyTip::createUserDailyTip($user['id'], $newDailyTip['id'], $assessment['id']);

                                $message = 'Your New Daily Tip';

                                event(new NewDailyTip($user['id'], 'new daily tip', $message));

                                Notification::createNotification('Daily Tip', $message, $user['device_token'], $user['id'], 1, Admin::DAILY_TIP_NOTIFICATION, Admin::B2C_NOTIFICATION, null, true);

                                UserActionService::dispatch(
                                    $user['id'],
                                    UserActions::NEW_DAILY_TIP,
                                    ['message' => $message]
                                );

                                ActivityLogger::addLog('new daily tip', "$message");

                                HaiChatHelpers::syncUserRecordWithHAi();

                            }

                            $userDailyTip = UserDailyTip::getLatestTip();

                            $isRead = $userDailyTip['is_read'];

                            if ($user['plan_name'] == 'Freemium') {


                                $data = [
                                    'daily_tip_id' => $userDailyTip['daily_tip_id'],
                                    'title' => $userDailyTip['dailyTip']['title'] ?? '',
                                    'description' => $userDailyTip['dailyTip']['description'] ?? '',
                                    'is_read' => $isRead,
                                    'favorite_daily_tip' => $userDailyTip['favorite_tip'],
                                    'created_at' => $isRead == 1 ? $userDailyTip['tip_completed_at'] : null,
                                ];

                            } else {

                                $data = [
                                    'daily_tip_id' => $userDailyTip['daily_tip_id'],
                                    'title' => $userDailyTip['dailyTip']['title'] ?? '',
                                    'description' => $userDailyTip['dailyTip']['description'] ?? '',
                                    'is_read' => $isRead,
                                    'favorite_daily_tip' => $userDailyTip['favorite_tip'],
                                    'created_at' => $isRead == 1 ? $userDailyTip['tip_completed_at'] : null,
                                    'nextTipTime' => !empty($nextTipTime) ? $nextTipTime->format('Y-m-d H:i:s.u T (P)') : null,
                                    'currentTime' => !empty($currentTime) ? $currentTime->format('Y-m-d H:i:s.u T (P)') : null,
                                    'check' => $updatedWithinDay ?? null

                                ];
                            }

                            return Helpers::successResponse('Daily Tip', $data);

                        }

                    }

                } while ($newDailyTip && $latestTip && $latestTip['is_read'] == 1 && $latestTip['updated_at'] >= now()->subYear()

                );

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

            ActivityLogger::addLog('Favorite Daily Tip', $message);

            return Helpers::successResponse($message);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function getFavoriteDailyTip(Request $request)
    {
        try {

            $favoriteTips = UserDailyTip::getUserFavoriteDailyTip($request->input('pagination'), $request->input('per_page'));

            return Helpers::successResponse('Your favorite daily tips', $favoriteTips, $request->input('pagination'));

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function getPodcasts()
    {
        try {

            $podcasts = Podcast::getAllAudioFiles();

            $audioFiles = [];

            foreach ($podcasts as $podcast) {

                $playList = PlaylistLog::getSinglePodcastItem($podcast['id']);

                $audioFiles[] = [
                    'id' => $podcast['id'] ?? null,
                    'my_playlist' => !empty($playList) ? 1 : 0,
                    'title' => $podcast['title'] ?? null,
                    'audio_id' => $podcast['audio_id'] ?? null,
                    'audio_url' => Helpers::extractFilePath($podcast['audio_url'] ?? null),
                    'thumbnail_url' => Helpers::extractFilePath($podcast['thumbnail_url'] ?? null, 'url'),
                ];

            }

            return Helpers::successResponse('Podcast list', $audioFiles);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }


    public function coreStats(Request $request)
    {

        try {

            $assessment = Assessment::singleAssessmentFromId($request->input('assessment_id', null));

            $coreState = Assessment::getCoreStatev4($assessment, Helpers::getUser()->date_of_birth);

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

//                $point = PointHelper::addPointsOnDailyTipRead();
            }

            ActivityLogger::addLog('Read Daily Tip', "Read Daily Tip");

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

            $user = Helpers::getUser();

            $userPlan = ($user->plan_name == Admin::FREEMIUM_TEXT) ? Admin::FREEMIUM_TEXT : Admin::PREMIUM_PLAN_NAME;

            $assessmentId = $request->input('assessment_id');

            $assessment = $assessmentId

                ? Assessment::where('id', $assessmentId)->where('user_id', $user->id)->first()

                : Assessment::getLatestAssessment($user->id);

            if (empty($assessment)) {

                return Helpers::validationResponse('Assessment not found');

            }

            $actionPlan = ActionPlan::getActionPlanByAssessmentId($assessment, $userPlan)

                ?: ActionPlan::storeUserActionPlan($assessment, $userPlan);

            $plan = OptimizationPlan::getSinglePlan($actionPlan['priority'], $userPlan);

            if (!$plan) {

                return Helpers::validationResponse('Plan not found');

            }

            $tz = $user->timezone ?? config('app.timezone');

            $minutes = Helpers::explodeTimezoneWithHoursAndMinutes($tz);

            if (!$actionPlan || !$actionPlan->updated_at) {

                $updatedAt = null;

            } else {

                $updatedAt = Carbon::parse($actionPlan->updated_at)->addMinutes($minutes)->startOfMinute();

            }

            $currentDate = Carbon::now()->addMinutes($minutes)->startOfMinute();

            $days = $updatedAt && $updatedAt <= $currentDate ? $updatedAt->diffInDays($currentDate) + 1 : 0;

            $optimizationWindow = ($user->plan_name == Admin::PREMIUM_PLAN_NAME)

                ? self::ASSESSMENT_DAYS

                : self::FREE_ASSESSMENT_DAYS;

            $progress = min($days, $optimizationWindow);

            $overall = $optimizationWindow > 0 ? round(($progress / $optimizationWindow) * 100, 2) : 0;

            if ($userPlan === Admin::PREMIUM_PLAN_NAME) {

                $phaseData = [
                    'phase_1' => null,
                    'phase_2' => null,
                    'phase_3' => null,
                ];

                if ($days <= 30) {

                    $phaseData['phase_1'] = $plan->day1_30;

                    $currentPhase = 'Phase 1 - Day ' . $days . ' of 30';

                } elseif ($days <= 60) {

                    $phaseData['phase_2'] = $plan->day31_60;

                    $currentPhase = 'Phase 2 - Day 31 ' . $days . ' of 60';

                } else {

                    $phaseData['phase_3'] = $plan->day61_90;

                    $currentPhase = 'Phase 3 - Day 61 ' . $days . ' of 90';

                }

                $response = [
                    'id' => $plan->id,
                    'priority' => $plan->priority,
                    'type' => $plan->type,
                    'plan_text' => [
                        'intro' => $plan->ninty_days_plan,
                        'phase_1' => [
                            'name' => 'Foundation',
                            'text' => $phaseData['phase_1']
                        ],
                        'phase_2' => [
                            'name' => 'Integration',
                            'text' => $phaseData['phase_2']
                        ],
                        'phase_3' => [
                            'name' => 'Mastery',
                            'text' => $phaseData['phase_3']
                        ],
                        'current_phase' => $currentPhase,
                        'overall' => $overall,
                    ],
                ];

            } else {

                $response = [
                    'id' => $plan->id,
                    'priority' => $plan->priority,
                    'type' => $plan->type,
                    'plan_text' => $plan->fourteen_days_plan,
                    'overall' => $overall,
                ];

            }

            return Helpers::successResponse('Action plan', $response);

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

                $optionalTraitDetail = CodeDetail::getOptimalTraitDetail($optionalTrait['trait']);

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

                    $label = match ($styleNumber) {
                        1 => 'Primary',
                        2 => 'Secondary',
                        3 => 'Tertiary',
                        default => '',
                    };

                    $trait = AssessmentWalkThrough::getbyCodeName(strtoupper($styleKey), $styleNumber);

                    $getTrait['public_name'] = $trait['public_name'];
                    $getTrait['code_name'] = $trait['code_name'];
                    $getTrait['overview'] = [
                        'title' => "Your {$label} trait is the {$trait['public_name']} Trait! [Overview]",
                        'description' => $trait['overview'],
                    ];
                    $getTrait['optimal'] = [
                        'title' => "Your Highest and Optimal Expression of your {$trait['public_name']} Trait",
                        'description' => $trait['optimal'],
                    ];
                    $getTrait['optimization'] = [
                        'title' => "Optimization Hot Spots And Things To Recognize As Natural Triggers for your {$trait['public_name']} Trait",
                        'description' => $trait['optimization'],
                    ];

                    $traits[] = $getTrait;

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

                ActivityLogger::addLog('Assessment Walkthrough', "Assessment walkthrough completed.");

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

    public function CheckShareData(Request $request)
    {

        try {

            if (!empty($request['company_name'])) {

                $checkData = B2BBusinessCandidates::checkShareDataDetail($request['company_name']);

                if (!empty($checkData)) {

                    if ($checkData['share_data'] == Admin::DECLINED_DATA) {

                        $data = [
                            'shared_data' => Admin::DECLINED_DATA,
                            'company_name' => $request['company_name'],
                            'status' => $checkData['role'] == Admin::IS_TEAM_MEMBER ? 'member' : 'candidate',
                        ];

                        return Helpers::successResponse('Check Shared Data', $data);
                    }

                    $data = [
                        'shared_data' => Admin::SHARED_DATA,
                        'company_name' => $request['company_name']
                    ];

                    return Helpers::successResponse('Check Shared Data', $data);

                } else {

                    return Helpers::validationResponse('Data not found.');

                }

            } else {
                $pedingShareData = B2BBusinessCandidates::getPendingSharedDataLoginUserCompanies(Helpers::getUser()['id']);

                $finalData = [];

                foreach ($pedingShareData as $pendingData) {
                    $finalData[] = [
                        'shared_data' => $pendingData->share_data,
                        'company_name' => $pendingData->businessUsers->company_name ?? null,
                        'status' => $pendingData->role == Admin::IS_TEAM_MEMBER ? 'member' : 'candidate',
                    ];
                }

                return Helpers::successResponse('Check Shared Data', $finalData);


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

    public function notSharedData(ShareDataRequest $request)
    {
        try {

            $data = B2BBusinessCandidates::AllCompaniesCheckShareDataDetail($request['company_name'], $request['candidate_id']);

            if (!empty($data)) {

                foreach ($data as $shared) {

                    B2BBusinessCandidates::notShareDataWithBusiness($shared['business_id'], $request['candidate_id']);

                }

                return Helpers::successResponse('Data Not Shared');

            } else {

                return Helpers::validationResponse('Data not found.');

            }

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

                $resource['resource_created_at'] = $resource['created_at'] ? Carbon::parse($resource['created_at'])->format('m/d/Y h:i A') : null;

                $formatedResources[] = $resource;
            }

            return Helpers::successResponse('Latest resources', $formatedResources);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function announcementNews()
    {
        try {

            $announcements = AnnouncementNews::getAnnouncementNews();

            return Helpers::successResponse('All Announcement & News', $announcements);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function recentPlayer()
    {
        try {

            $recentPlayer = MultiMediaStats::getPlayer(Helpers::getUser()['id']);

            return Helpers::successResponse('Recent Player', $recentPlayer);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function addRecentPlayer(AddRecentPlayerRequest $request)
    {
        try {

            MultiMediaStats::addOrUpdateRecentPlayer($request->all());

            return Helpers::successResponse('Recent Player added successfully');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function suggestedItemForYou()
    {
        try {
            $user = Helpers::getUser();

            $currentSuggestion = SuggestionForYou::checkSuggestion($user['id']);

            $suggestionForYou = null;

            if (!empty($currentSuggestion)) {

                $difference = Carbon::now()->diffInDays($currentSuggestion['created_at']);

                if ($difference > 0) {
                    $allSuggestion = SuggestionForYou::allSuggestion($user['id']);

                    $getSuggestedItem = SuggestedItem::getSingleSuggestedItem($user['id'], $allSuggestion);

                    if (!empty($getSuggestedItem)) {

                        SuggestionForYou::createSuggestion($user['id'], $getSuggestedItem['id']);
                        $suggestionForYou = SuggestionForYou::checkSuggestion($user['id']);
                    }
                } else {
                    $suggestionForYou = $currentSuggestion;
                }
            } else {

                $getSuggestedItem = SuggestedItem::getSingleSuggestedItem($user['id']);

                if (!empty($getSuggestedItem)) {
                    SuggestionForYou::createSuggestion($user['id'], $getSuggestedItem['id']);
                    $suggestionForYou = SuggestionForYou::checkSuggestion($user['id']);
                }
            }

            $formatted = [
                'id' => $suggestionForYou['suggestedItem']['id'] ?? null,
                'title' => $suggestionForYou['suggestedItem']['title'] ?? null,
                'description' => $suggestionForYou['suggestedItem']['description'] ?? null,
                'module_type' => $suggestionForYou['suggestedItem']['module_type'] ?? null,
                'created_at' => $suggestionForYou['created_at'] ?? null,
                'updated_at' => $suggestionForYou['updated_at'] ?? null,
//                'video_url' => $suggestionForYou['suggestedItem']['video_url']['path'] ?? null,
//                'audio_url' => $suggestionForYou['suggestedItem']['audio_url']['path'] ?? null,
//                'photo_url' => $suggestionForYou['suggestedItem']['photo_url']['url'] ?? null,
            ];

            return Helpers::successResponse('HumanOp Shop Suggested Items', $formatted);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function haiChatStatus()
    {
        try {
            return Helpers::successResponse(
                'HAI CHAT status fetched successfully',
                [
                    'status' => $this->user->hai_chat
                ]
            );
        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function haiChatSound()
    {
        try {

            $user = Helpers::getUser();

            $status = User::haiChatSound($user);

            if ($status == Admin::HAI_CHAT_MUTE) {

                return Helpers::successResponse('HAi chat mute successfully');

            } else {

                return Helpers::successResponse('HAi chat unmute successfully');

            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public static function allCompanies()
    {
        try {

            $user = Helpers::getUser();

            $companies = User::allCompanies($user);

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
                $data = [];

                foreach ($futureConsideration as $consideration) {

                    $data[] = [
                        'company_name' => $consideration['businessUsers']['company_name'] ?? null,
                        'user_type' => $consideration['role'] == Admin::IS_CANDIDATE ? 'Candidate' : 'Member',
                    ];
                }

                return Helpers::successResponse('Future Consideration', $data);
            }

            return Helpers::validationResponse('You are not Future Consideration');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function futureConsiderationShareData(ShareDataRequest $request)
    {
        try {

            $data = B2BBusinessCandidates::AllCompaniesCheckShareDataDetail($request['company_name'], $request['candidate_id']);

            if (!empty($data)) {

                foreach ($data as $shared) {

                    B2BBusinessCandidates::futureConsiderationShareDataWithBusiness($shared['business_id'], $request['candidate_id']);

                }

                return Helpers::successResponse('Data Shared Successfully');

            } else {

                return Helpers::validationResponse('Data not found.');

            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function premiumLifetimeWelcome(Request $request)
    {
        try {

            User::changedPremiumLifetime();

            return Helpers::successResponse('status changed');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function premiumLifetimeBanner()
    {
        try {

            $banner = LifetimeDealBanner::latest()->first();

            return Helpers::successResponse('Premium Lifetime Deal Banner', $banner);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function skipPremiumLifetimeBanner()
    {
        try {

            $banner = User::skipBanner();

            return Helpers::successResponse('Skip Premium Lifetime Deal Banner');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public static function feedbackStatus()
    {
        return helpers::successResponse('Webhook fine');
    }

    public function hotSpots(Request $request)
    {

        try {

            if ($request->has('assessment_id')) {

                $assessment = Assessment::getSingleAssessment($request->input('assessment_id'));

            } else {

                $assessment = Assessment::getLatestAssessment(Helpers::getUser()['id']);

            }

            if (!empty($assessment)) {

                $hotSpots = HotSpotsPlan::getActionPlanByAssessmentId($assessment);

                if (empty($hotSpots)) {

                    $hotSpots = HotSpotsPlan::storeUserActionPlan($assessment);

                }

                return Helpers::successResponse('90 Days Hot Spots', $hotSpots);

            }

            return Helpers::validationResponse('Assessment not found');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function energyShieldStatus()
    {
        try {

            $user = Helpers::getUser();

            $tz = $user->timezone ?? config('app.timezone');

            if (strpos($tz, '-') !== false) {
                $parts = explode('-', $tz);
                $tz = trim(end($parts));
            }

            $userPlan = ($user->plan_name == Admin::FREEMIUM_TEXT) ? Admin::FREEMIUM_TEXT : Admin::PREMIUM_PLAN_NAME;

            $assessment = Assessment::getLatestAssessment($user->id);

            $actionPlan = ActionPlan::getActionPlanByAssessmentId($assessment, $userPlan);

            if ($actionPlan) {

                $userTime = Carbon::parse($actionPlan->updated_at)->timezone($tz)->startOfMinute();

                $currentTime = Carbon::now()->timezone($tz)->startOfMinute();

                $optimizationDays = $userTime ? $userTime->diffInDays($currentTime) + 1 : 0;

            } else {
                $optimizationDays = 0;

            }

            $assessment = Assessment::where('user_id', $user->id)->latest('updated_at')->first(['id', 'page', 'web_page', 'app_page', 'updated_at']);

            if (!$assessment) {

                $remainingDays = null;

            } else {

                $pageValues = [$assessment->page, $assessment->web_page, $assessment->app_page];

                if (collect($pageValues)->every(fn($val) => $val != 0 || is_null($val))) {

                    $remainingDays = null;

                } else {

                    $assessmentTime = Carbon::parse($assessment->updated_at)->timezone($tz)->startOfMinute();

                    $currentTime = Carbon::now()->timezone($tz)->startOfMinute();

                    $daysPassed = $assessmentTime->diffInDays($currentTime);

                    $remainingDays = max(0, self::ASSESSMENT_DAYS - $daysPassed);

                }

            }

            $optimizationWindow = $user->plan_name == Admin::PREMIUM_PLAN_NAME ? self::ASSESSMENT_DAYS : self::FREE_ASSESSMENT_DAYS;

            return Helpers::successResponse('Energy shield status', [
                'optimization_days' => $optimizationWindow,
                'user_optimization_days' => $optimizationDays,
                'next_assessment' => $remainingDays,
                'daily_sync_streak' => DailySyncStreak::getUserDailySyncStreak($user->id) ?? 0
            ]);

        } catch (\Throwable $e) {

            return Helpers::serverErrorResponse($e->getMessage());

        }

    }

    public function updateUserSync(Request $request)
    {
        $validated = $request->validate([
            'variable_sync' => 'required|boolean'
        ]);

        $user = $this->user;

        if (!$user) {
            return Helpers::unauthResponse('User not authenticated');
        }

        $variable_sync_string = User::updateVariableSync(
            $user,
            $validated['variable_sync']
        );

        return Helpers::successResponse('Variable Sync Updated', $variable_sync_string);
    }
}
