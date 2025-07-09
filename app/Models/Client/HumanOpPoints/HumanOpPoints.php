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

        $getPoint = self::where('user_id', $user['id'])->first();

        if (is_null($getPoint)) {

            $getPoint =  self::create([
                'user_id' => $user['id'],
                'points' => Admin::COMPLETE_ASSESSMENT_POINT_FOR_CLARITY,
            ]);

            Helpers::checkAndTakePerformanceLevel($user);

            return $getPoint;

        }

        $getPoint->points += Admin::COMPLETE_ASSESSMENT_POINT_FOR_CLARITY;

        $getPoint->save();

        Helpers::checkAndTakePerformanceLevel($user);

        return $getPoint;

    }

    public static function addPointsAfterCompleteWatchVideo($user = null)
    {

        $getPoint = self::where('user_id', $user['id'])->first();

        $getPoint->points += Admin::COMPLETE_WATCH_VIDEO_POINT_FOR_CLARITY;

        $getPoint->save();

        Helpers::checkAndTakePerformanceLevel($user);

        return $getPoint;
    }

    public static function addPointsAfterCompleteAllWatchVideos($user = null)
    {

        $getPoint = self::where('user_id', $user['id'])->first();

        $getPoint->points += Admin::COMPLETE_ALL_WATCH_VIDEOS_POINT_FOR_CLARITY;

        $getPoint->save();

        Helpers::checkAndTakePerformanceLevel($user);

        return $getPoint;
    }

    public static function addPointsAfterCompleteDailyTip($user = null)
    {

        $getPoint = self::where('user_id', $user['id'])->first();

        $getPoint->points += Admin::COMPLETE_DAILY_TIP_POINT_FOR_CLARITY;

        $getPoint->save();

        Helpers::checkAndTakePerformanceLevel($user);

        return $getPoint;
    }

    public static function createOrUpdateUserPoints($user = null, $currentTime = null)
    {
        $checkPoint = self::getUserPoints($user);

        if ($checkPoint === null) {

            self::create([
                'user_id' => $user['id'],
                'points' => Admin::DAILY_LOGIN_POINT_FOR_CLARITY,
            ]);

            Helpers::checkAndTakePerformanceLevel($user);

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

                $checkPoint->points += Admin::DAILY_LOGIN_POINT_FOR_CLARITY;

                $checkPoint->save();

                Helpers::checkAndTakePerformanceLevel($user);

            } else {

                LoginStreaks::updateLoginStreak($user);

                $streak = LoginStreaks::getStreak($user);

                if (is_array($streak)) {

                    $streak = (object) $streak;

                }

                $bonus = Admin::DAILY_LOGIN_POINT_FOR_CLARITY + ($streak->login_days - 1);

                $checkPoint->points += $bonus;

                $checkPoint->save();

                Helpers::checkAndTakePerformanceLevel($user);

                return $checkPoint;

            }

        } elseif ($diffDays > 1) {

            $streak->login_days = 1;

            $streak->save();

            $checkPoint->points += Admin::DAILY_LOGIN_POINT_FOR_CLARITY;

            $checkPoint->save();

            Helpers::checkAndTakePerformanceLevel($user);

            return $checkPoint;

        } else {

            return null;

        }

    }

    public static function deductPoint($user = null, $points = null)
    {
        $point =  self::where('user_id', $user['id'])->first();

        $point->points -= $points;

        $point->save();

        return $point;
    }
}
