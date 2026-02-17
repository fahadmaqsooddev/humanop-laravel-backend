<?php

namespace App\Models\v4\Client\Gamification;

use App\Enums\Admin\Admin;
use App\Helpers\ActivityLogs\ActivityLogger;
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

    public static function getHaiMedal($userId = null)
    {
        return self::where('user_id', $userId)->where('medals', 'HAi Initiator')->latest()->first();
    }

    public static function getMedal($userId = null, $medal = null)
    {
        return self::where('user_id', $userId)->where('medals', $medal)->first();
    }

    public static function addMedalAfterCompleteWatchVideos($userId = null)
    {

        $getBadge = self::getMedal($userId, Admin::WATCH_VIDEO_MEDAL);

        $medal = Admin::WATCH_VIDEO_MEDAL;

        if (empty($getBadge))
        {

            ActivityLogger::addLog('Humanop Medal', "You have achieved {$medal} Humanop Video Badge for watching all videos.");

            return self::create([
                'user_id' => $userId,
                'medals' => $medal,
            ]);
        }

    }
}
