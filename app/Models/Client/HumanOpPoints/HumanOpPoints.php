<?php

namespace App\Models\Client\HumanOpPoints;

use App\Enums\Admin\Admin;
use App\Helpers\ActivityLogs\ActivityLogger;
use App\Helpers\Helpers;
use App\Models\Client\Point\Point;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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

        if ($user['plan_name'] === 'Premium' && $user['beta_breaker_club'] == Admin::BETA_BREAKER_CLUB) {

            $multiplier = 5;

        } elseif ($user['plan_name'] === 'Freemium' && $user['beta_breaker_club'] == Admin::BETA_BREAKER_CLUB) {

            $multiplier = 3;

        } elseif ($user['plan_name'] === 'Freemium') {

            $multiplier = ($user['beta_breaker_club'] == Admin::BETA_BREAKER_CLUB) ? 2 : 1;

        } elseif ($user['plan_name'] === 'Beta Breaker') {

            $multiplier = 2;

        } else {

            $multiplier = 3;

        }

        $basePoint = Admin::COMPLETE_ASSESSMENT_POINT_FOR_CLARITY;

        $pointsToAdd = $multiplier * $basePoint;

        Log::info(['check point' => $pointsToAdd]);

        $getPoint = self::where('user_id', $user['id'])->first();

        if (is_null($getPoint)) {

            $getPoint = self::create([
                'user_id' => $user['id'],
                'points' => $pointsToAdd,
            ]);

        } else {

            $getPoint->points += $pointsToAdd;

            $getPoint->save();

        }

        ActivityLogger::addLog('Assessment Point', "You have earned {$pointsToAdd} Humanop Points for assessment completion.");

        Log::info(['check point' => $getPoint]);

        Helpers::checkAndTakePerformanceLevel($user);

        Log::info(['check point' => $getPoint]);

        return $getPoint;
    }

    public static function addPointsAfterCompleteWatchVideo($user = null, $videoName = null)
    {

        if ($user['plan_name'] === 'Premium' && $user['beta_breaker_club'] == Admin::BETA_BREAKER_CLUB) {

            $multiplier = 5;

        } elseif ($user['plan_name'] === 'Freemium' && $user['beta_breaker_club'] == Admin::BETA_BREAKER_CLUB) {

            $multiplier = 3;

        } elseif ($user['plan_name'] === 'Freemium') {

            $multiplier = ($user['beta_breaker_club'] == Admin::BETA_BREAKER_CLUB) ? 2 : 1;

        } elseif ($user['plan_name'] === 'Beta Breaker') {

            $multiplier = 2;

        } else {

            $multiplier = 3;

        }

        $basePoint = Admin::COMPLETE_WATCH_VIDEO_POINT_FOR_CLARITY;

        $pointsToAdd = $multiplier * $basePoint;

        $getPoint = self::where('user_id', $user['id'])->first();

        $getPoint->points += $pointsToAdd;

        $getPoint->save();

        ActivityLogger::addLog('Humanop Point', "You have earned {$pointsToAdd} Humanop Points for watching the '{$videoName}' video.");

        Helpers::checkAndTakePerformanceLevel($user);

        return $getPoint;
    }

    public static function addPointsAfterCompleteAllWatchVideos($user = null)
    {

        if ($user['plan_name'] === 'Premium' && $user['beta_breaker_club'] == Admin::BETA_BREAKER_CLUB) {

            $multiplier = 5;

        } elseif ($user['plan_name'] === 'Freemium' && $user['beta_breaker_club'] == Admin::BETA_BREAKER_CLUB) {

            $multiplier = 3;

        } elseif ($user['plan_name'] === 'Freemium') {

            $multiplier = ($user['beta_breaker_club'] == Admin::BETA_BREAKER_CLUB) ? 2 : 1;

        } elseif ($user['plan_name'] === 'Beta Breaker') {

            $multiplier = 2;

        } else {

            $multiplier = 3;

        }

        $basePoint = Admin::COMPLETE_ALL_WATCH_VIDEOS_POINT_FOR_CLARITY;

        $pointsToAdd = $multiplier * $basePoint;

        $getPoint = self::where('user_id', $user['id'])->first();

        $getPoint->points += $pointsToAdd;

        $getPoint->save();

        ActivityLogger::addLog('Humanop Point', "You have earned {$pointsToAdd} Humanop Points for watching all videos.");

        Helpers::checkAndTakePerformanceLevel($user);

        return $getPoint;
    }

    public static function addPointsAfterCompleteDailyTip($user = null)
    {

        if ($user['plan_name'] === 'Premium' && $user['beta_breaker_club'] == Admin::BETA_BREAKER_CLUB) {

            $multiplier = 5;

        } elseif ($user['plan_name'] === 'Freemium' && $user['beta_breaker_club'] == Admin::BETA_BREAKER_CLUB) {

            $multiplier = 3;

        } elseif ($user['plan_name'] === 'Freemium') {

            $multiplier = ($user['beta_breaker_club'] == Admin::BETA_BREAKER_CLUB) ? 2 : 1;

        } elseif ($user['plan_name'] === 'Beta Breaker') {

            $multiplier = 2;

        } else {

            $multiplier = 3;

        }

        $basePoint = Admin::COMPLETE_DAILY_TIP_POINT_FOR_CLARITY;

        $pointsToAdd = $multiplier * $basePoint;

        $getPoint = self::where('user_id', $user['id'])->first();

        $getPoint->points += $pointsToAdd;

        $getPoint->save();

        ActivityLogger::addLog('Humanop Point', "You have earned {$pointsToAdd} Humanop Points for completing the daily tips.");

        Helpers::checkAndTakePerformanceLevel($user);

        return $getPoint;
    }

    public static function createOrUpdateUserPoints($user = null, $currentTime = null)
    {

        if ($user['plan_name'] === 'Premium' && $user['beta_breaker_club'] == Admin::BETA_BREAKER_CLUB) {

            $multiplier = 5;

        } elseif ($user['plan_name'] === 'Freemium' && $user['beta_breaker_club'] == Admin::BETA_BREAKER_CLUB) {

            $multiplier = 3;

        } elseif ($user['plan_name'] === 'Freemium') {

            $multiplier = ($user['beta_breaker_club'] == Admin::BETA_BREAKER_CLUB) ? 2 : 1;

        } elseif ($user['plan_name'] === 'Beta Breaker') {

            $multiplier = 2;

        } else {

            $multiplier = 3;

        }

        $basePoint = Admin::DAILY_LOGIN_POINT_FOR_CLARITY;

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

            $streak = (object)$streak;

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

                    $streak = (object)$streak;

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

        $point = self::where('user_id', $userId)->first();

        $point->points -= $points;

        $point->save();

        return $point;
    }

    public static function purchaseHAiCreditsFromHp($hp)
    {
        $user = Helpers::getUser();

        $points = self::getUserPoints($user)?->points;

        if ($points >= $hp) {

            self::deductPoint($user->id, $hp);

            Point::purchaseHAiCreditsFromHp($hp);

            ActivityLogger::addLog('Deduct Point', "Deducted {$hp} Humanop Points to purchase HAi credits.");

            return Helpers::successResponse("Credit purchased.");
        }

        throw new \Exception("You do not have enough HP to make this purchase.");
    }

}
