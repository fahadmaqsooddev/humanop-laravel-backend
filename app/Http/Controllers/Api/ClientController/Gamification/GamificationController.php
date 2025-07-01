<?php

namespace App\Http\Controllers\Api\ClientController\Gamification;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\CompleteWatchVideoRequest;
use App\Models\Client\Gamification\GamificationBadgesAchievement;
use App\Models\Client\Gamification\GamificationMedalRewards;
use App\Models\Client\HumanOpPoints\HumanOpPoints;
use App\Models\Client\HumanOpPoints\LoginStreaks;
use App\Models\Videos\VideoProgress;
use Illuminate\Http\Request;

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

            $streak = LoginStreaks::getStreak($user);

            $loginStreak['steaks'] = $streak['login_days'];

            return Helpers::successResponse("Your Login Streak", $loginStreak);

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

    public function completeWatchVideo(CompleteWatchVideoRequest $request)
    {
        try {
            $watchVideo = VideoProgress::completeWatchVideo($request['assessment_id'], $request['video_name']);

            if (!empty($watchVideo)) {

                return Helpers::successResponse("Congratulations! You completed watching the video: {$watchVideo['video_name']}.");

            } else {

                return Helpers::validationResponse('Watch video failed or already completed.');

            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public static function currentUserBadge()
    {
        try {

            $user = Helpers::getUser() ?? Helpers::getWebUser();

            $currentBadge = GamificationBadgesAchievement::currentBadge($user['id']);

            $badge['current_badge'] = $currentBadge['badges'] ?? null;

            return Helpers::successResponse("Your current badge", $badge);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }


    public static function currentUserMedal()
    {
        try {

            $user = Helpers::getUser() ?? Helpers::getWebUser();

            $currentMedal = GamificationMedalRewards::currentMedal($user['id']);

            $medal['current_medal'] = $currentMedal['medals'] ?? null;

            return Helpers::successResponse("Your current Medal", $medal);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public static function getBadgesAndMedals()
    {
        try {

            $user = Helpers::getUser() ?? Helpers::getWebUser();

            $medals = GamificationMedalRewards::allMedalRewards($user['id']);

            $badges = GamificationBadgesAchievement::getBadgeAchievements($user['id']);

            $data = [
                'medals' => $medals,
                'badges' => $badges
            ];

            return Helpers::successResponse("Your Medals and Badges", $data);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

}
