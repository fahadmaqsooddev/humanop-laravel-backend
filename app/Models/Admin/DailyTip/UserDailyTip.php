<?php

namespace App\Models\Admin\DailyTip;

use App\Enums\Admin\Admin;
use App\Events\DailyTip\NewDailyTip;
use App\Helpers\Helpers;
use App\Models\Admin\Notification\Notification;
use App\Models\Client\HumanOpPoints\HumanOpPoints;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDailyTip extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public function dailyTip()
    {
        return $this->hasOne(DailyTip::class, 'id', 'daily_tip_id');
    }

    public function dailyTips()
    {
        return $this->hasMany(DailyTip::class, 'id', 'daily_tip_id');
    }

    public static function getLatestTip()
    {

        $user = Helpers::getWebUser() ?? Helpers::getUser();

        if (!$user) {
            return collect();
        }

        return self::where('user_id', $user->id)->with('dailyTip')->latest()->first();
    }

    public static function removeUserTip($user_id)
    {
        return self::where('user_id', $user_id)->delete();
    }

    public static function createUserDailyTip($user_id = null, $daily_tip_id = null, $assessment_id = null)
    {
        self::create([
            'user_id' => $user_id,
            'daily_tip_id' => $daily_tip_id,
            'assessment_id' => $assessment_id
        ]);

        return self::getLatestTip();
    }

    public static function userFavoriteDailyTip($tipId = null)
    {
        $userId = Helpers::getUser()['id'] ?? Helpers::getWebUser()['id'];

        $userTip = self::where('user_id', $userId)->where('daily_tip_id', $tipId)->first();

        if (!empty($userTip)) {

            $userTip->update(['favorite_tip' => $userTip->favorite_tip == 1 ? Admin::FAVORITE_DAILY_TIP : Admin::NOT_FAVORITE_DAILY_TIP]);

            return $userTip;
        }

        return false;
    }

    public static function getUserFavoriteDailyTip()
    {

        $userId = Helpers::getUser()['id'] ?? Helpers::getWebUser()['id'];

        return self::where('user_id', $userId)->where('favorite_tip', 2)->with('dailyTips')->orderBy('updated_at', 'DESC')->get();
    }

    public static function getUserCompletedDailyTip()
    {

        $userId = Helpers::getUser()['id'] ?? Helpers::getWebUser()['id'];

        return self::where('user_id', $userId)->where('is_read', 1)->with('dailyTips')->orderBy('updated_at', 'DESC')->get();
    }

    public static function readUserDailyTip()
    {

        $user = Helpers::getUser();

        $daily_tip = self::where('user_id', Helpers::getWebUser()->id ?? Helpers::getUser()->id)->latest()->first();

        $daily_tip_read = $daily_tip->is_read ?? 1;

        if ($daily_tip['is_read'] == 0) {

            $daily_tip->update(['is_read' => 1]);

            HumanOpPoints::addPointsAfterCompleteDailyTip($user);

            $message = 'Your New Daily Tip';

            event(new NewDailyTip($user['id'], 'new daily tip', $message));

            Helpers::OneSignalApiUsed($user['id'], 'new daily tip', $message);

            Notification::createNotification('Daily Tip', $message, $user['device_token'], $user['id'], 1, Admin::DAILY_TIP_NOTIFICATION, Admin::B2C_NOTIFICATION);

        }

        return $daily_tip_read;
    }

    public static function userDailytip($daily_tip = null)
    {

        $user = Helpers::getWebUser() ?? Helpers::getUser();

        return UserDailyTip::where('user_id', $user['id'])->where('daily_tip_id', $daily_tip)->latest()->first();
    }
}
