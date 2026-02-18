<?php

namespace App\Models\v4\Client\Gamification;

use App\Enums\Admin\Admin;
use App\Helpers\v4\ActivityLogs\ActivityLogger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GamificationBadgesAchievement extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function currentBadge($userId = null)
    {
        return self::where('user_id', $userId)->latest()->first();
    }

    public static function getBadgeAchievements($userId = null)
    {
        return self::where('user_id', $userId)->get();
    }

    public static function getBadge($userId = null, $badge = null)
    {
        return self::where('user_id', $userId)->where('badges', $badge)->first();
    }

    public static function addBadgeAfterCompleteAssessment($userId = null)
    {

        $getBadge = self::getBadge($userId, Admin::ASSESSMENT_BADGES);

        $badge = Admin::ASSESSMENT_BADGES;

        if (empty($getBadge))
        {

            ActivityLogger::addLog('Humanop Badge', "You have achieved {$badge} Humanop Assessment Badge for completing the assessment.");

           return self::create([
                'user_id' => $userId,
                'badges' => $badge,
            ]);
        }

    }


    public static function addBadgeAfterCompleteWatchVideos($userId = null)
    {

        $getBadge = self::getBadge($userId, Admin::WATCH_VIDEO_BADGES);

        $badge = Admin::WATCH_VIDEO_BADGES;

        if (empty($getBadge))
        {

            ActivityLogger::addLog('Humanop Badge', "You have achieved {$badge} Humanop Video Badge for watching all videos.");

            return self::create([
                'user_id' => $userId,
                'badges' => $badge,
            ]);
        }

    }
}
