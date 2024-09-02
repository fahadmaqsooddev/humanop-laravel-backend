<?php

namespace App\Models\Admin\DailyTip;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Models\Assessment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TipRecord;

class DailyTip extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public function tip(){

        return $this->hasOne(TipRecord::class,'tip_id','id')->whereNot('user_id', Helpers::getUser()->id);
    }

    public static function getTip()
    {
        return self::get();
    }

    public static function createTip($data = null)
    {
        return self::create($data);
    }

    public static function getSingleTip()
    {

        $tip = self::where('user_id', Helpers::getWebUser()->id)->first();

        return $tip;

    }


    public static function dailyTip(){

        return self::where('user_id', Helpers::getUser()->id)->first();
    }

    public static function updateUserDailyTip(){

//        $today_tip = self::where('user_id', Helpers::getUser()->id ?? Helpers::getWebUser()->id)
//
//            ->whereDate('updated_at', Carbon::today())->exists();

//        if (!$today_tip){

            $assessmentDetails = Assessment::getAssessment();

            $body = ['assessment_details' => $assessmentDetails];

            $daily_tip = GuzzleHelpers::sendRequestFromGuzzle('post', 'http://44.201.128.253:8000/daily_tip',$body);

            $tip = self::where('user_id', Helpers::getWebUser()->id ?? Helpers::getUser()->id)->first();

            if ($tip){

                $tip->update(['description' => $daily_tip]);

            }else{

                self::create([
                    'user_id' => Helpers::getWebUser()->id ?? Helpers::getUser()->id,
                    'description' => $daily_tip
                ]);
            }

//        }

    }

}
