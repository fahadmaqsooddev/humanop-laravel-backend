<?php

namespace App\Models\Admin\DailyTip;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Models\Assessment;
use App\Models\Client\Plan\Plan;
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

        $user = Helpers::getUser() ?? Helpers::getWebUser();

//        $today_tip = self::where('user_id', $user->id)
//
//            ->whereDate('updated_at', Carbon::today())->exists();
//
//        if (!$today_tip){

            $assessmentDetails = Assessment::getAssessment();

            $plan = Plan::singlePlan($user->subscription('main')->stripe_price ?? "price_1PuwhBRxOqsngfBOk9G5SYBo");

            $body = ['assessment_details' => $assessmentDetails, 'status' => ($plan['name'] ?? "Freemium"),'code' => 0];

            $daily_tip = GuzzleHelpers::sendRequestFromGuzzle('post', 'http://44.201.128.253:8000/daily_tip',$body);

            $tip = self::where('user_id', Helpers::getWebUser()->id ?? Helpers::getUser()->id)->first();

            if ($tip){

                $tip->update(['description' => $daily_tip, 'text' => $daily_tip, 'is_read' => 0]);

            }else{

                self::create([
                    'user_id' => Helpers::getWebUser()->id ?? Helpers::getUser()->id,
                    'description' => $daily_tip,
                    'text' => $daily_tip
                ]);
            }

//        }

    }

    public static function readUserDailyTip(){

        $daily_tip = self::where('user_id', Helpers::getWebUser()->id ?? Helpers::getUser()->id)->first();

        $daily_tip_read = $daily_tip->is_read ?? 1;

        if ($daily_tip){

            $daily_tip->update(['is_read' => 1]);
        }

        return $daily_tip_read;
    }

}
