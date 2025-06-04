<?php

namespace App\Models\Client\Point;

use App\Helpers\Helpers;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function storePoint($data = null)
    {
        self::create($data);
    }

    public static function userExists($user_id = null)
    {
        return self::where('user_id', $user_id)->first();
    }

    public static function updatePoint($user_id = null, $point = null)
    {
        $userCredits = self::where('user_id', $user_id)->first();

//        $userCredits->update(['point' => $userCredits['point'] + $point]);

        return $userCredits;

    }

    public static function getPoints(){

        $points = self::where('user_id', Helpers::getUser()->id)->first();

        $pointLogs = PointLog::query()->where('user_id', Helpers::getUser()->id)->where('type', PointLog::HAI_Credit);

        if ($points){

                $total_tokens = (clone $pointLogs)->whereMonth('created_at', Carbon::now()->month)->where('is_added', 1)->sum('point') * 1000;
                $used_tokens = (clone $pointLogs)->whereMonth('created_at', Carbon::now()->month)->where('is_added', 0)->sum('point') * 1000;
                $remaining_tokens = $points['point'] * 1000;
                $rollover_tokens = ((clone $pointLogs)->whereMonth('created_at', Carbon::now()->subMonth())->where('is_added', 1)->sum('point') - (clone $pointLogs)->whereMonth('created_at', Carbon::now()->subMonth())->where('is_added', 0)->sum('point')) * 1000;
        }

        return [
            'total_tokens' => $total_tokens ?? 0,
            'used_tokens' => $used_tokens ?? 0,
            'remaining_tokens' => $remaining_tokens ?? 0,
            'rollover_tokens' => $rollover_tokens ?? 0,
        ];

    }

    public static function addPoints($points){

        $record = self::where('user_id', Helpers::getUser()->id)->first();

        if ($record){

            $record->increment('point', $points);

        }else{

            self::create([
                'user_id' => Helpers::getUser()->id,
                'point' => $points,
            ]);
        }

        PointLog::createPointLog($points, 1);

    }
}
