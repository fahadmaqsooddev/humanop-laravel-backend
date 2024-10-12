<?php

namespace App\Models\Admin\DailyTip;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Helpers\Practitioner\PractitionerHelpers;
use App\Models\Assessment;
use App\Models\Client\Plan\Plan;
use App\Models\User;
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

            self::hitDailyTipApiAndUpdateUserTip($user);
//        }

    }

    public static function hitDailyTipApiAndUpdateUserTip($user){

        $assessmentDetails = Assessment::getAssessment();

        $plan = Plan::singlePlan($user->subscription('main')->stripe_price ?? "price_1PuwhBRxOqsngfBOk9G5SYBo");

        if (!empty($user->practitioner_id)){

            $user = User::user($user->practitioner_id);

            $url = url('/') . '/' . $user->first_name . '/' . $user->last_name . '/intro-assessment';

        }else{

            $url = url('/client/intro-assessment');
        }

        $body = ['assessment_url' => $url, 'assessment_details' => $assessmentDetails, 'status' => ($plan['name'] ?? "Freemium"),'code' => 0];

        $daily_tip = GuzzleHelpers::sendRequestFromGuzzle('post', 'http://44.201.128.253:8000/daily_tip',$body);

        $tip = self::where('user_id', $user->id)->first();

        if ($tip){

            $tip->update(['description' => $daily_tip[0], 'text' => $daily_tip[1], 'is_read' => 0]);

        }else{

            self::create([
                'user_id' => Helpers::getWebUser()->id ?? Helpers::getUser()->id,
                'description' => $daily_tip[0],
                'text' => $daily_tip[1]
            ]);
        }

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
