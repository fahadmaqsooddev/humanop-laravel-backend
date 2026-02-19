<?php

namespace App\Http\Controllers\Api\ClientController\Gamification;

use App\Helpers\ActivityLogs\ActivityLogger;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\CompleteWatchVideoRequest;
use App\Http\Requests\Api\Client\Gamification\PurchaseCreditsFromHp;
use App\Models\Admin\DailyTip\UserDailyTip;
use App\Models\Assessment;
use App\Models\Client\Gamification\GamificationBadgesAchievement;
use App\Models\Client\Gamification\GamificationMedalRewards;
use App\Models\Client\Gamification\GamificationPerformanceLevel;
use App\Models\Client\Hai\HaiThread;
use App\Models\Client\HumanOpPoints\HumanOpPoints;
use App\Models\Client\HumanOpPoints\LoginStreaks;
use App\Models\HAIChai\HaiChat;
use App\Models\Videos\VideoProgress;
use Illuminate\Support\Facades\DB;

class GamificationController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public static function loginStreaks()
    {
        try {
            $user = Helpers::getUser() ?? Helpers::getWebUser();

            if (!$user) {
                return Helpers::unauthResponse("User not authenticated");
            }

            $streak = LoginStreaks::getStreak($user);

            return Helpers::successResponse("Your Login Streak", ['streaks' => $streak['login_days']]);

        } catch (\Exception $exception) {
            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }


    public function getHp()
    {
        try {
            $user = Helpers::getWebUser() ?? Helpers::getUser();

            if (!$user) {

                return Helpers::unauthResponse('User not authenticated');
            }

            $points = HumanOpPoints::getUserPoints($user);

            return Helpers::successResponse('Your HumanOp Points', ['points' => $points['points']]);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }


    public function completeWatchVideo(CompleteWatchVideoRequest $request)
    {
        try {

            $data = $request->validated();

            $watchVideo = VideoProgress::completeWatchVideo($data['assessment_id'], $data['video_name']);

            if (!empty($watchVideo)) {

                $videoTitle = ucwords(str_replace('_', ' ', $watchVideo['video_name']));

                ActivityLogger::addLog('Assessment watch video', "Congratulations! You completed watching the video: {$videoTitle}.");

                return Helpers::successResponse("Congratulations! You completed watching the video: {$videoTitle}.");

            } else {

                return Helpers::validationResponse('Watch video failed or already completed.');

            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function currentChallenges()
    {
        try {
            $user = Helpers::getUser() ?? Helpers::getWebUser();

            if (!$user) {

                return Helpers::unauthResponse("User not authenticated");
            }

            $completedTips = UserDailyTip::getUserCompletedDailyTip();

            $latestAssessment = Assessment::getLatestAssessment($user['id']);

            if (!$latestAssessment) {

                return Helpers::validationResponse("No assessment found for this user.");
            }

            $assessmentId = $latestAssessment['id'];

            $allVideos = VideoProgress::getRecords($assessmentId);

            $watchedVideos = VideoProgress::checkAllWatchVideos($assessmentId);

            $haiConversations = HaiChat::getUserChats();

            $challenges = [
                'daily_tips' => count($completedTips),
                'total_videos' => count($allVideos),
                'watch_videos' => count($watchedVideos),
                'hai_conversation' => min(count($haiConversations), 3),
            ];

            return Helpers::successResponse('Current Challenges', $challenges);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }


    public function unfinishedChallenges()
    {
        try {

            $user = Helpers::getUser() ?? Helpers::getWebUser();

            if (!$user) {

                return Helpers::unauthResponse("User not authenticated");
            }

            $latestAssessment = Assessment::getLatestAssessment($user['id']);

            if (!$latestAssessment) {
                return Helpers::validationResponse("No assessment found for this user.");
            }

            $assessmentId = $latestAssessment['id'];

            $dailyTip = UserDailyTip::getLatestTip();

            $dailyTipStatus = (!empty($dailyTip) && $dailyTip['is_read'] == 1) ? 'finished' : 'unfinished';

            $allVideos = VideoProgress::getRecords($assessmentId);

            $watchedVideos = VideoProgress::checkAllWatchVideos($assessmentId);

            $videoStatus = (count($allVideos) > 0 && count($watchedVideos) < count($allVideos)) ? 'unfinished' : 'finished';

            $haiConversationStatus = count(HaiChat::getUserChats()) > 2 ? 'finished' : 'unfinished';

            $challenges = [
                'daily_tips' => $dailyTipStatus,
                'watch_videos' => $videoStatus,
                'hai_conversation' => $haiConversationStatus,
            ];

            return Helpers::successResponse('Current Challenges', $challenges);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function currentUserBadge()
    {
        try {
            $user = Helpers::getUser() ?? Helpers::getWebUser();

            if (!$user) {

                return Helpers::unauthResponse("User not authenticated");
            }

            $currentBadge = GamificationBadgesAchievement::currentBadge($user['id']);

            return Helpers::successResponse("Your current badge", ['current_badge' => $currentBadge['badges'] ?? null]);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }



    public function currentUserMedal()
    {
        try {
            $user = Helpers::getUser() ?? Helpers::getWebUser();

            if (!$user) {

                return Helpers::unauthResponse("User not authenticated");
            }

            $currentMedal = GamificationMedalRewards::currentMedal($user['id']);

            return Helpers::successResponse("Your current Medal", [
                'current_medal' => $currentMedal['medals'] ?? null,
            ]);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }


    public function getBadgesAndMedals()
    {
        try {

            $user = Helpers::getUser() ?? Helpers::getWebUser();

            if (!$user) {

                return Helpers::unauthResponse("User not authenticated");
            }

            return Helpers::successResponse("Your Medals and Badges", [
                'medals' => GamificationMedalRewards::allMedalRewards($user['id']),
                'badges' => GamificationBadgesAchievement::getBadgeAchievements($user['id']),
            ]);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }




    public function getPerformanceLevel()
    {
        try {

            $user = Helpers::getUser() ?? Helpers::getWebUser();

            if (!$user) {

                return Helpers::unauthResponse("User not authenticated");
            }

            $data = GamificationPerformanceLevel::getSinglePerformanceLevel($user);

            return Helpers::successResponse("Your Performance Level", $data);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public static function purchaseHaiCreditsFromHp(PurchaseCreditsFromHp $request)
    {
        try {

            return DB::transaction(function () use ($request) {

                return HumanOpPoints::purchaseHAiCreditsFromHp($request->integer('hp'));

            });
        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }


}
