<?php

namespace App\Models\Client\Point;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class PointLog extends Model
{
    use HasFactory;
    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    public static function storePointLog($data = null){
              self::create($data);
    }

    public static function checkTodayLogin($user_id){
          return   self::where('user_id',$user_id)->where('type',0)->whereDate('created_at', Carbon::today())->exists();
    }

    public static function checkLogForConsecutiveDays($user_id,$days,$plan){
        return   self::where('user_id', $user_id)
            ->where('type', 0)
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->where('plan',$plan)
            ->orderBy('created_at', 'asc') // Order by date
            ->count();
    }

    public static function checkLastLoginReward($user_id,$days,$plan){
        return   self::where('user_id', $user_id)
            ->where('type', 1)
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->where('plan',$plan)
            ->orderBy('created_at', 'asc') // Order by date
            ->count();
    }
}
