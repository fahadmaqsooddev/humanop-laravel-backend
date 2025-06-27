<?php

namespace App\Http\Controllers\Api\ClientController\Gamification;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Client\Gamification\GamificationBadgesAchievement;
use App\Models\Client\Gamification\GamificationMedalRewards;
use App\Models\Client\HumanOpPoints\LoginStreaks;
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

}
