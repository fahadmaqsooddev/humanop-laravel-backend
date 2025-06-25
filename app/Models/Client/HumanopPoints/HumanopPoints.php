<?php

namespace App\Models\CLient\HumanopPoints;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HumanopPoints extends Model
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

    public static function addPointsAfterCompleteAssessment($userId = null)
    {

        $getPoint = self::where('user_id', $userId)->first();

        $getPoint->points += Admin::COMPLETE_ASSESSMENT_POINT_FOR_CLARITY;

        $getPoint->save();

        return $getPoint;
    }

    public static function createOrUpdateUserPoints($user = null, $currentTime = null)
    {
        $checkPoint = self::getUserPoints($user);

        if ($checkPoint === null) {

            // First-time point assignment
            self::create([
                'user_id' => $user['id'],
                'points' => Admin::DAILY_LOGIN_POINT_FOR_CLARITY,
            ]);

            return LoginStreaks::startLoginStreak($user);
        }

        $streak = LoginStreaks::getStreak($user);

        if ($currentTime->diffInDays($user['last_login']) === 1) {

            // Continued streak
            if ($streak['login_days'] > 6) {

                // Reset streak if it exceeds 7 days
                $streak['login_days'] = 1;

                $streak['complete_streaks'] += 1;

                $streak->save();

                $checkPoint->points += Admin::DAILY_LOGIN_POINT_FOR_CLARITY;

            } else {

                // Update streak and add bonus points
                LoginStreaks::updateLoginStreak($user);

                $streak = LoginStreaks::getStreak($user);

                $bonus = Admin::DAILY_LOGIN_POINT_FOR_CLARITY + ($streak['login_days'] - 1);

                $checkPoint->points += $bonus;

                $checkPoint->save();

                return $checkPoint;
            }

        } elseif ($currentTime->diffInDays($user['last_login']) > 1) {

            // Missed a day or other reset case
            $streak['login_days'] = 1;

            $streak->save();

            $checkPoint->points += Admin::DAILY_LOGIN_POINT_FOR_CLARITY;

            $checkPoint->save();

            return $checkPoint;

        }else
        {
            return null;
        }

    }

}
