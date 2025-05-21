<?php

namespace App\Helpers\Points;

use App\Helpers\Helpers;
use App\Models\Client\Point\{Point, PointLog};
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PointHelper
{
    public static function addPoints(int $user_id = null, int $points = null): bool
    {
        try {

            $existingPoints = Point::userExists($user_id);

            $data = ['user_id' => $user_id, 'point' => $existingPoints ? $existingPoints->point + $points : $points];

            if ($existingPoints) {

                Point::updatePoint($user_id, $data);

            } else {

                Point::storePoint($data);

            }

            return true;

        } catch (Exception $e) {

            return false;

        }

    }

    public static function addPointsToLog(int $user_id = null, $plan = null): bool
    {
        try {
            $planDetail = self::getPointDetail($plan);

            $alreadyLoggedInToday = PointLog::checkTodayLogin($user_id);

            if (!$alreadyLoggedInToday) {

                PointLog::storePointLog(['user_id' => $user_id, 'point' => $planDetail['point'], 'plan' => $plan]);

                Session::flash('add_point', $planDetail['point']);

                if (PointLog::checkLogForConsecutiveDays($user_id, $planDetail['days'], $plan) >= $planDetail['days'] && PointLog::checkLastLoginReward($user_id, $planDetail['days'], $plan) < 1) {

                    PointLog::storePointLog(['user_id' => $user_id, 'point' => $planDetail['sequential_point'], 'type' => 1, 'plan' => $plan]);

                    self::addPoints($user_id, $planDetail['sequential_point']);

                    Session::flash('add_point', $planDetail['sequential_point'] + $planDetail['point']);

                }

                return self::addPoints($user_id, $planDetail['point'], $plan);

            }

            return false;

        } catch (Exception $e) {

            Log::error("Failed to log points for user $user_id: " . $e->getMessage());

            return false;

        }

    }

    public static function addPointsOnDailyTipRead()
    {

        $user = Helpers::getWebUser() ?? Helpers::getUser();

        $data['user_id'] = $user->id;

        $data['plan'] = $user['plan_name'];

        $data['type'] = 2;

        if ($user['plan_name'] === 'Freemium') {

            $data['point'] = 1;

        } elseif ($user['plan_name'] === 'Core') {

            $data['point'] = 2;

        } elseif ($user['plan_name'] === 'Premium') {

            $data['point'] = 4;

        } else {

            $data['point'] = 0;
        }

        PointLog::storePointLog($data);

        PointHelper::addPoints($user->id, $data['point']);

        return $data['point'];
    }

    public static function getPointDetail($plan)
    {
        if ($plan == 'Freemium') {

            $points = 1;

            $days = 90;

            $sequential_points = 90;

        } else if ($plan == 'Core') {

            $points = 2;

            $days = 30;

            $sequential_points = 40;

        } else if ($plan == 'Premium') {

            $points = 4;

            $days = 7;

            $sequential_points = 10;

        }

        return ['point' => $points, 'days' => $days, 'sequential_point' => $sequential_points];

    }

    public static function addPointsOnFeedbackSubmission($user = null)
    {

        $data['user_id'] = $user['id'];

        $data['plan'] = $user['plan_name'];

        $data['type'] = 3; // for feedback submission

        $data['point'] = 1;

        PointLog::storePointLog($data);

        PointHelper::addPoints($user->id, $data['point']);

        return $data['point'];

    }

}
