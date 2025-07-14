<?php

namespace App\Models\Client\Gamification;

use App\Enums\Admin\Admin;
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

        if (empty($getBadge))
        {
           return self::create([
                'user_id' => $userId,
                'badges' => Admin::ASSESSMENT_BADGES,
            ]);
        }

    }


    public static function addBadgeAfterCompleteWatchVideos($userId = null)
    {

        $getBadge = self::getBadge($userId, Admin::WATCH_VIDEO_BADGES);

        if (empty($getBadge))
        {
            return self::create([
                'user_id' => $userId,
                'badges' => Admin::WATCH_VIDEO_BADGES,
            ]);
        }

    }
}
