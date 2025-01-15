<?php

namespace App\Models\Admin\DailyTip;

use App\Events\DailyTip\NewDailyTip;
use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Helpers\Practitioner\PractitionerHelpers;
use App\Models\Admin\Notification\Notification;
use App\Models\Assessment;
use App\Models\AssessmentColorCode;
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

    public function tip()
    {
        return $this->hasOne(TipRecord::class, 'tip_id', 'id')->whereNot('user_id', Helpers::getUser()->id);
    }

    public function userTip()
    {

        return $this->hasOne(UserDailyTip::class, 'daily_tip_id', 'id')->where('user_id', Helpers::getWebUser()->id);
    }

    public static function getTip()
    {
        return self::get();
    }

    public static function allTips()
    {
        return self::whereNotNull('code')->orderBy('created_at', 'desc');
    }

    public static function createTip($data = null)
    {
        if ($data['subscription_type'] == 'Freemium') {
            foreach ($data['code'] as $key => $code) {
                $data['code'] = $key;
                $data['min_point'] = $code['min'];
                $data['max_point'] = $code['max'];
            }
        } else {
            $codes = [];
            $min_points = [];
            $max_points = [];
            foreach ($data['code'] as $key => $code) {
                $codes[] = $key; // Add the key to the codes array
                $min_points[] = $code['min']; // Add min to the min_points array
                $max_points[] = $code['max']; // Add max to the max_points array
            }
            $data['code'] = implode(',', $codes);
            $data['min_point'] = implode(',', $min_points);
            $data['max_point'] = implode(',', $max_points);
        }


        return self::create($data);
    }

    public static function updateIntentionPlan($data = null, $id = null)
    {
        if ($data['subscription_type'] == 'Freemium') {
            foreach ($data['code'] as $key => $code) {
                $data['code'] = $key;
                $data['min_point'] = $code['min'];
                $data['max_point'] = $code['max'];
            }
        } else {
            $codes = [];
            $min_points = [];
            $max_points = [];
            foreach ($data['code'] as $key => $code) {
                $codes[] = $key; // Add the key to the codes array
                $min_points[] = $code['min']; // Add min to the min_points array
                $max_points[] = $code['max']; // Add max to the max_points array
            }
            $data['code'] = implode(',', $codes);
            $data['min_point'] = implode(',', $min_points);
            $data['max_point'] = implode(',', $max_points);
        }

        $daily_tip = self::find($id);


        $daily_tip->update($data);

        return $daily_tip;
    }

    public static function deleteDailyTip($id)
    {
        self::whereId($id)->delete();
    }

    public static function getSingleTip()
    {

        $tip = self::where('user_id', Helpers::getWebUser()->id)->first();

        return $tip;

    }

    public static function getTodayTip()
    {
        $user = Helpers::getWebUser() ?? Helpers::getUser();


        $assessment = Assessment::getLatestAssessment($user['id']);


        if (!empty($assessment)) {

            $userDailyTip = UserDailyTip::getLatestTip($user['id']);

            if ($userDailyTip && $userDailyTip['assessment_id'] == $assessment['id']) {

                $dayCheck = $userDailyTip->created_at >= now()->subDay();


                if ($dayCheck) {
                    return $userDailyTip->dailyTip;
                }

            }
            else{

                if ($assessment) {

                    $codeColor = AssessmentColorCode::getGreenCodes($assessment['id']);

                    $alchemy = Assessment::getAlchemy($assessment);

                    if ($alchemy) {

                        $codeAlchemy = $alchemy['code'];

                    }

                    $communication = Assessment::getEnergy($assessment);

                    if ($communication) {

                        $codeCommunication = $communication[0];

                    }

                    $selectedCodeList = [
                        $codeColor['code'] ?? '',
                        $codeAlchemy ?? '',
                        $codeCommunication ?? ''
                    ];

                    $randomCode = $selectedCodeList[array_rand($selectedCodeList)];


                    if ($randomCode) {

                        $newDailyTip = DailyTip::getSameCodeTips($randomCode);

                        if ($newDailyTip) {

                            $latestTip = UserDailyTip::where('user_id', $user['id'])->where('daily_tip_id', $newDailyTip['id'])
                                ->latest()
                                ->first();

                            $alreadyExist = $latestTip && $latestTip->created_at >= Carbon::now()->subDays(365);

                            if ($alreadyExist) {

                                self::getTodayTip();

                            }
                            $newUserDailyTip = UserDailyTip::createUserDailyTip($user['id'], $newDailyTip['id'], $assessment['id']);

                            if (!empty($newDailyTip))
                            {
                                $message = 'Your New daily tip';

                                $deviceToken = $user['device_token'];

                                event(new NewDailyTip($user['id'], 'New Daily Tip', $message));

                                Notification::createNotification('New Daily Tip', $message, $deviceToken, $user['id'], 1);

                            }

                            $todayTip = DailyTip::findTip($newUserDailyTip['daily_tip_id']);

                            return $todayTip;
                        }
                    }
                }
            }

        } else {
            return '';

        }

    }

    public static function dailyTip()
    {

        return self::where('user_id', Helpers::getUser()->id)->first();
    }

    public static function updateUserDailyTip()
    {

        $user = Helpers::getUser() ?? Helpers::getWebUser();

        $today_tip = self::where('user_id', $user->id)
            ->whereDate('updated_at', Carbon::today())->exists();

        if (!$today_tip) {

            self::hitDailyTipApiAndUpdateUserTip($user);
        }

    }

//    public static function hitDailyTipApiAndUpdateUserTip($user){
//
//        $assessmentDetails = Assessment::getAssessment();
//
//        $plan = Plan::singlePlan($user->subscription('main')->stripe_price ?? "price_1PuwhBRxOqsngfBOk9G5SYBo");
//
//        if (!empty($user->practitioner_id)){
//
//            $user = User::user($user->practitioner_id);
//
//            $url = url('/') . '/' . $user->first_name . '/' . $user->last_name . '/intro-assessment';
//
//        }else{
//
//            $url = url('/client/intro-assessment');
//        }
//
//        $body = ['assessment_url' => $url, 'assessment_details' => $assessmentDetails, 'status' => ($plan['name'] ?? "Freemium"),'code' => 0];
//
//        $daily_tip = GuzzleHelpers::sendRequestFromGuzzle('post', 'http://44.201.128.253:8000/daily_tip',$body);
//
//        $tip = self::where('user_id', $user->id)->first();
//
//        if ($tip){
//
//            $tip->update(['description' => $daily_tip[0], 'text' => $daily_tip[1], 'is_read' => 0]);
//
//        }else{
//
//            self::create([
//                'user_id' => Helpers::getWebUser()->id ?? Helpers::getUser()->id,
//                'description' => $daily_tip[0],
//                'text' => $daily_tip[1]
//            ]);
//        }
//
//    }

    public static function readUserDailyTip()
    {

        $daily_tip = self::where('user_id', Helpers::getWebUser()->id ?? Helpers::getUser()->id)->first();

        $daily_tip_read = $daily_tip->is_read ?? 1;

        if ($daily_tip) {

            $daily_tip->update(['is_read' => 1]);
        }

        return $daily_tip_read;
    }

    public static function getSameCodeTips($code = null)
    {
        return self::where('code', $code)->inRandomOrder()->first();
    }

    public static function findTip($id = null)
    {
        return self::where('id', $id)->with('userTip')->first();
    }
}
