<?php

namespace App\Models\Admin\DailyTip;

use App\Helpers\Helpers;
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

    public static function getLatestTip()
    {

        return self::where('user_id', Helpers::getWebUser()->id ?? Helpers::getUser()->id)->with('dailyTip')->latest()->first();
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

    public static function readUserDailyTip()
    {

        $daily_tip = self::where('user_id', Helpers::getWebUser()->id ?? Helpers::getUser()->id)->latest()->first();

        $daily_tip_read = $daily_tip->is_read ?? 1;

        if ($daily_tip) {
            $daily_tip->update(['is_read' => 1]);
        }

        return $daily_tip_read;
    }

    public static function userDailytip($daily_tip = null)
    {

        $user = Helpers::getWebUser() ?? Helpers::getUser();

        return UserDailyTip::where('user_id', $user['id'])->where('daily_tip_id', $daily_tip)->latest()->first();
    }
}
