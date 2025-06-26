<?php

namespace App\Http\Controllers\Api\ClientController\HpRewards;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Client\HumanOpPoints\LoginStreaks;
use Illuminate\Http\Request;

class HpRewardsController extends Controller
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
}
