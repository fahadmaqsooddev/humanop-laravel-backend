<?php

namespace App\Models\Client\Gamification;

use App\Enums\Admin\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GamificationMedalRewards extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function currentMedal($userId = null)
    {
        return self::where('user_id', $userId)->latest()->first();
    }

    public static function allMedalRewards($userId = null)
    {
        return self::where('user_id', $userId)->get();
    }

    public static function getMedal($userId = null, $medal = null)
    {
        return self::where('user_id', $userId)->where('medals', $medal)->first();
    }

    public static function addMedalAfterCompleteWatchVideos($userId = null)
    {

        $getBadge = self::getMedal($userId, Admin::WATCH_VIDEO_MEDAL);

        if (empty($getBadge))
        {
            return self::create([
                'user_id' => $userId,
                'medals' => Admin::WATCH_VIDEO_MEDAL,
            ]);
        }

    }
}
