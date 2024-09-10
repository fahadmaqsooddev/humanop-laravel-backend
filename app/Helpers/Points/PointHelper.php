<?php

namespace App\Helpers\Points;

use App\Models\Client\Point\{Point, PointLog};
use Exception;
use Illuminate\Support\Facades\Log;

class PointHelper
{
    /**
     * Add points to a user's account, updating or creating a new entry as needed.
     *
     * @param int $user_id
     * @param int $points
     * @return bool
     */
    public static function addPoints(int $user_id, int $points): bool
    {
        try {
            // Check if the user already has points
            $existingPoints = Point::userExists($user_id);
            // Prepare data for updating or creating points
            $data = ['user_id' => $user_id, 'point' => $existingPoints ? $existingPoints->point + $points : $points];

            // Update if exists, otherwise create new
            if ($existingPoints) {
                Point::updatePoint($user_id, $data);
            } else {
                Point::storePoint($data);
            }
            return true; // Operation successful
        } catch (Exception $e) {
            // Log any errors for further investigation
            Log::error("Failed to add points for user $user_id: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Add points to the log for today's activity and update the user's points.
     *
     * @param int $user_id
     * @param int $points
     * @return bool
     */
    public static function addPointsToLog(int $user_id, $plan): bool
    {
        try {
            $planDetail = self::getPointDetail($plan);

            // Check if the user has already logged points today
            $alreadyLoggedInToday = PointLog::checkTodayLogin($user_id);

            if (!$alreadyLoggedInToday) {

                // Store the log entry for today's points
                PointLog::storePointLog(['user_id' => $user_id, 'point' => $planDetail['point'],'plan' => $plan]);
                if(PointLog::checkLogForConsecutiveDays($user_id,$planDetail['days'],$plan) >= $planDetail['days'] && PointLog::checkLastLoginReward($user_id,$planDetail['days'],$plan) < 1){
                    PointLog::storePointLog(['user_id' => $user_id, 'point' => $planDetail['sequential_point'],'type' => 1,'plan' => $plan]);
                    self::addPoints($user_id, $planDetail['sequential_point']);
                }
                // Add points to the user's account
                return self::addPoints($user_id, $planDetail['point'],$plan);
            }
            return false; // Points already logged for today
        } catch (Exception $e) {
            // Log any errors for further investigation
            Log::error("Failed to log points for user $user_id: " . $e->getMessage());
            return false;
        }
    }
    public static function getPointDetail($plan){
        if($plan == 'Freemium'){
            $points = 1;
            $days = 90;
            $sequential_points = 90;
        }else if($plan == 'Core'){
            $points = 2;
            $days = 30;
            $sequential_points = 40;
        }else if($plan == 'Premium'){
            $points = 4;
            $days = 7;
            $sequential_points = 10;
        }
        return ['point' => $points,'days' => $days,'sequential_point' => $sequential_points];
    }
}
