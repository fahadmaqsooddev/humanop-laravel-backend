<?php

namespace App\Models\Client\HumanOpPoints;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Models\Client\Gamification\GamificationPerformanceLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HumanOpPoints extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function getUserPoints($user = null)
    {
        return self::where('user_id', $user['id'])->first();
    }

    public static function addPointsAfterCompleteAssessment($user = null)
    {

        $plan = $user['plan_name'];

        $basePoint = Admin::COMPLETE_ASSESSMENT_POINT_FOR_CLARITY;

        $multiplier = match ($plan) {
            'Freemium' => 1,
            'Core'     => 3,
            default    => 5,
        };

        $pointsToAdd = $multiplier * $basePoint;

        $getPoint = self::where('user_id', $user['id'])->first();

        if (is_null($getPoint)) {

            $getPoint = self::create([
                'user_id' => $user['id'],
                'points'  => $pointsToAdd,
            ]);

        } else {

            $getPoint->points += $pointsToAdd;

            $getPoint->save();

        }

        Helpers::checkAndTakePerformanceLevel($user);

        return $getPoint;
    }

    public static function addPointsAfterCompleteWatchVideo($user = null)
    {

        $plan = $user['plan_name'];

        $basePoint = Admin::COMPLETE_WATCH_VIDEO_POINT_FOR_CLARITY;

        $multiplier = match ($plan) {
            'Freemium' => 1,
            'Core'     => 3,
            default    => 5,
        };

        $pointsToAdd = $multiplier * $basePoint;

        $getPoint = self::where('user_id', $user['id'])->first();

        $getPoint->points += $pointsToAdd;

        $getPoint->save();

        Helpers::checkAndTakePerformanceLevel($user);

        return $getPoint;
    }

    public static function addPointsAfterCompleteAllWatchVideos($user = null)
    {

        $plan = $user['plan_name'];

        $basePoint = Admin::COMPLETE_ALL_WATCH_VIDEOS_POINT_FOR_CLARITY;

        $multiplier = match ($plan) {
            'Freemium' => 1,
            'Core'     => 3,
            default    => 5,
        };

        $pointsToAdd = $multiplier * $basePoint;

        $getPoint = self::where('user_id', $user['id'])->first();

        $getPoint->points += $pointsToAdd;

        $getPoint->save();

        Helpers::checkAndTakePerformanceLevel($user);

        return $getPoint;
    }

    public static function addPointsAfterCompleteDailyTip($user = null)
    {

        $plan = $user['plan_name'];

        $basePoint = Admin::COMPLETE_DAILY_TIP_POINT_FOR_CLARITY;

        $multiplier = match ($plan) {
            'Freemium' => 1,
            'Core'     => 3,
            default    => 5,
        };

        $pointsToAdd = $multiplier * $basePoint;

        $getPoint = self::where('user_id', $user['id'])->first();

        $getPoint->points += $pointsToAdd;

        $getPoint->save();

        Helpers::checkAndTakePerformanceLevel($user);

        return $getPoint;
    }

    public static function createOrUpdateUserPoints($user = null, $currentTime = null)
    {

        $plan = $user['plan_name'];

        $basePoint = Admin::DAILY_LOGIN_POINT_FOR_CLARITY;

        $multiplier = match ($plan) {
            'Freemium' => 1,
            'Core'     => 3,
            default    => 5,
        };

        $pointsToAdd = $multiplier * $basePoint;

        $checkPoint = self::getUserPoints($user);

        if ($checkPoint === null) {

            self::create([
                'user_id' => $user['id'],
                'points' => $pointsToAdd,
            ]);

            return LoginStreaks::startLoginStreak($user);

        }

        $streak = LoginStreaks::getStreak($user);

        if (!$streak) {

            return LoginStreaks::startLoginStreak($user);

        }

        if (is_array($streak)) {

            $streak = (object) $streak;

        }

        $diffDays = $currentTime->diffInDays($user['last_login']);

        if ($diffDays === 1) {

            if ($streak->login_days > 6) {

                $streak->login_days = 1;

                $streak->complete_streaks += 1;

                $streak->save();

                $checkPoint->points += $pointsToAdd;

                $checkPoint->save();

                $user->last_login = $currentTime;

                $user->save();

                Helpers::checkAndTakePerformanceLevel($user);

            } else {

                LoginStreaks::updateLoginStreak($user);

                $streak = LoginStreaks::getStreak($user);

                if (is_array($streak)) {

                    $streak = (object) $streak;

                }

                $bonus = $pointsToAdd + ($streak->login_days - 1);

                $checkPoint->points += $bonus;

                $checkPoint->save();

                $user->last_login = $currentTime;

                $user->save();

                Helpers::checkAndTakePerformanceLevel($user);

                return $checkPoint;

            }

        } elseif ($diffDays > 1) {

            $streak->login_days = 1;

            $streak->save();

            $checkPoint->points += $pointsToAdd;

            $checkPoint->save();

            $user->last_login = $currentTime;

            $user->save();

            Helpers::checkAndTakePerformanceLevel($user);

            return $checkPoint;

        } else {

            return null;

        }

    }

    public static function deductPoint($userId = null, $points = null)
    {
        $point =  self::where('user_id', $userId)->first();

        $point->points -= $points;

        $point->save();

        return $point;
    }
}
