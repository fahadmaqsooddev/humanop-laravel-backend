<?php

namespace App\Models\Client\Gamification;

use App\Enums\Admin\Admin;
use App\Models\Client\HumanOpPoints\HumanOpPoints;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GamificationPerformanceLevel extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }


    public static function getLevel($userId = null, $performance = null)
    {
        return self::where('user_id', $userId)->where('performance', $performance)->first();
    }

    public static function addFirstPerformanceLevel($userId = null)
    {
        $level = self::getLevel($userId, Admin::FIRST_LEVEL);

        if (empty($level))
        {
            return self::create([
                'user_id' => $userId,
                'performance' => Admin::FIRST_LEVEL,
                'level' => 1,
            ]);
        }
    }

    public static function addSecondPerformanceLevel($userId = null)
    {
        $level = self::getLevel($userId, Admin::SECOND_LEVEL);

        dd($level);
        
        if (empty($level))
        {
            return self::create([
                'user_id' => $userId,
                'performance' => Admin::SECOND_LEVEL,
                'level' => 2,
            ]);
        }
    }

    public static function getSinglePerformanceLevel($user = null)
    {
        $points = HumanOpPoints::getUserPoints($user)['points'];

        if ($points > 0 || $points < 500)
        {
            GamificationPerformanceLevel::addFirstPerformanceLevel($user['id']);

        }elseif ($points > 499 || $points < 1000){

            GamificationPerformanceLevel::addSecondPerformanceLevel($user['id']);

        }

        return self::where('user_id',$user['id'])->latest()->first();

    }

}
