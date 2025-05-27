<?php

namespace App\Models\Client\Dashboard;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Models\Assessment;
use Carbon\Carbon;
use http\Client\Curl\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionPlan extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    // query

//    public static function storeUserActionPlan($ignore_days_restriction = false)
//    {
//
//        $app_env = env('APP_ENV');
//
//        if ($app_env != 'production' || $app_env != 'staging') {
//            $user = Helpers::getWebUser() ?? Helpers::getUser();
//
//            $plan = $user['plan_name'];
//
//            $days_according_to_plan = $user['plan_name'] === 'Freemium' || $user['plan_name'] === 'Core' ?
//
//                $user['plan_name'] === 'Core' ? 30 : 90 : 7;
//
//            $user_action_plan = self::where('user_id', $user->id)->first();
//
//            if ($user_action_plan) {
//
////                if (Carbon::parse($user_action_plan['updated_at'])->addDays($days_according_to_plan)->lessThan(Carbon::today()) || $ignore_days_restriction) {
//
//                    $latestAssessment = Assessment::getLatestAssessment($user->id);
//
//                    if ($latestAssessment) {
//
//                        $assessmentDetails = Assessment::getAllRowGrid($latestAssessment->id);
//                    }
//
//                    $body = ['grid' => $assessmentDetails ?? null, 'plan' => $plan];
//
//                    $data = GuzzleHelpers::sendRequestFromGuzzle('post', 'http://54.227.7.149:8000/90day_plan', $body);
//
////                    $data = [
////                        '<h3>Coming Soon !</h3>',
////                        '<h3>Coming Soon !</h3>'
////                    ];
//
//                    $user_action_plan->update(['plan_text' => $data[0], 'text' => $data[1]]);
//
////                }
//
//            } else {
//
//                $latestAssessment = Assessment::getLatestAssessment($user->id);
//
//                if ($latestAssessment) {
//
//                    $assessmentDetails = Assessment::getAllRowGrid($latestAssessment->id);
//                }
//
//                $body = ['grid' => $assessmentDetails ?? null, 'plan' => $plan];
//
//                $data = GuzzleHelpers::sendRequestFromGuzzle('post', 'http://54.227.7.149:8000/90day_plan', $body);
//
////                $data = [
////                    '<h3>Coming Soon !</h3>',
////                    '<h3>Coming Soon !</h3>'
////                ];
//
//                self::create(['plan_text' => $data[0], 'text' => $data[1], 'user_id' => $user->id]);
//
//            }
//
//        }
//
//    }

    public static function checkUserActionPlan($assessment = null, $user = null)
    {
        if (!empty($assessment)) {

            if (!empty($user)) {

                $getUser = $user;

            }else{

                $getUser = Helpers::getWebUser() ?? Helpers::getUser();

            }

            $existingPlan = self::where('user_id', $getUser['id'])->latest()->first();

            if (!empty($existingPlan) && ($existingPlan['assessment_id'] == $assessment['id'])) {

                $minutes = Helpers::explodeTimezoneWithHours($getUser['timezone']);

                $userTime = Carbon::parse($existingPlan['updated_at'])->addMinutes($minutes * 60)->toDateTimeString();

                $difference = Carbon::now()->diffInDays($userTime);

                if ($difference > 90) {

                    self::storeUserActionPlan($assessment, $getUser);

                }

            } else {

                self::storeUserActionPlan($assessment, $getUser);

            }

        }

    }

    public static function storeUserActionPlan($assessment = null, $user = null)
    {

//        $checkActionPlan = self::getUserActionPlan($user['id']);
//
//        if (empty($checkActionPlan))
//        {
            $assessmentDetails = Assessment::getAllRowGrid($assessment['id']);

            $bridge = [];

            if (!empty($assessmentDetails['gridColor'])) {

                foreach ($assessmentDetails['gridColor'] as $key => $gridColor) {

                    if ($gridColor == 'border-green') {

                        $bridge[] = $key;

                    }

                }

            }

            $firstRowDriver = ['de', 'dom', 'fe', 'gre', 'lun', 'nai', 'ne', 'pow', 'sp', 'tra', 'van', 'wil'];

            $firstRowStyle = ['sa', 'ma', 'jo', 'lu', 'ven', 'mer', 'so'];

            $matchingKeys = array_intersect(array_keys($assessmentDetails['firstRow']), $firstRowDriver);

            $countFirstRowDriver = 0;

            foreach ($matchingKeys as $key) {

                $countFirstRowDriver += $assessmentDetails['firstRow'][$key];
            }

            $values = [
                $assessmentDetails['firstRow']['em'],
                $assessmentDetails['firstRow']['ins'],
                $assessmentDetails['firstRow']['int'],
                $assessmentDetails['firstRow']['mov']
            ];

            $countGreaterThan12 = count(array_filter($values, function ($value) {
                return $value > 12;
            }));

            $countLessThan7 = count(array_filter($values, function ($value) {
                return $value < 7;
            }));

            $authenticTraitCount = 0;

            foreach ($firstRowStyle as $trait) {
                if ($assessmentDetails['firstRow'][$trait] >= 5) { // Replace 'green' with the actual condition
                    $authenticTraitCount++;
                }
            }

            $inAuthenticDriverCount = 0;

            foreach ($firstRowDriver as $driver) {
                if (isset($assessmentDetails['gridColor'][$driver]) && in_array($assessmentDetails['gridColor'][$driver], ['red'])) {
                    $inAuthenticDriverCount++;
                }
            }

            $pilotDriverCount = 0;

            foreach ($firstRowDriver as $driver) {
                if (isset($assessmentDetails['gridColor'][$driver]) && ($assessmentDetails['gridColor'][$driver] == 'green') && ($assessmentDetails['firstRow'][$driver] < 3)) {
                    $pilotDriverCount++;
                }
            }

            $actionPlan = [];


            if ($assessmentDetails['firstRow']['van'] == 0)
            {

                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_1'),
                    'priority' => 'priority 1'
                ];

            }
            elseif ($assessmentDetails['firstRow']['sa'] == 0)
            {

                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_2.regal'),
                    'priority' => 'priority 2 regal'
                ];

            }
            elseif ($assessmentDetails['firstRow']['ma'] == 0)
            {
                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_2.energetic'),
                    'priority' => 'priority 2 energetic'
                ];

            }
            elseif ($assessmentDetails['firstRow']['jo'] == 0)
            {
                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_2.absorptive'),
                    'priority' => 'priority 2 absorptive'
                ];

            }
            elseif ($assessmentDetails['firstRow']['lu'] == 0)
            {
                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_2.romantic'),
                    'priority' => 'priority 2 romantic'
                ];

            }
            elseif ($assessmentDetails['firstRow']['ven'] == 0)
            {
                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_2.sympathetic'),
                    'priority' => 'priority 2 sympathetic'
                ];

            }
            elseif ($assessmentDetails['firstRow']['mer'] == 0)
            {

                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_2.perceptive'),
                    'priority' => 'priority 2 perceptive'
                ];

            }
            elseif ($assessmentDetails['firstRow']['so'] == 0)
            {
                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_2.effervescent'),
                    'priority' => 'priority 2 effervescent'
                ];

            }
            elseif (
                ($assessmentDetails['firstRow']['jo'] < 5 && $assessmentDetails['firstRow']['mer'] < 5 && $assessmentDetails['firstRow']['so'] < 5) &&
                ($assessmentDetails['thirdRow']['jo'] < 30 && $assessmentDetails['thirdRow']['mer'] < 30) &&
                ($assessmentDetails['firstRow']['so'] < 3)
            )
            {

                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_3'),
                    'priority' => 'priority 3'
                ];

            }
            elseif ($authenticTraitCount < 3)
            {
                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_4'),
                    'priority' => 'priority 4'
                ];

            }
            elseif
            (

                ($assessmentDetails['firstRow']['ma'] < 5 && $assessmentDetails['firstRow']['lu'] < 5) &&
                ($assessmentDetails['thirdRow']['ma'] < 30 && $assessmentDetails['thirdRow']['lu'] < 30)
            )
            {
                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_5'),
                    'priority' => 'priority 5'
                ];

            }
            elseif (

                ($assessmentDetails['firstRow']['sa'] < 5 && $assessmentDetails['firstRow']['ven'] < 5) &&
                ($assessmentDetails['thirdRow']['sa'] < 30 && $assessmentDetails['thirdRow']['ven'] < 30)
            )
            {

                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_6'),
                    'priority' => 'priority 6'
                ];

            }
            elseif ($inAuthenticDriverCount > 4)
            {
                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_7'),
                    'priority' => 'priority 7'
                ];

            }
            elseif ($inAuthenticDriverCount == 3)
            {
                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_8'),
                    'priority' => 'priority 8'
                ];

            }
            elseif ($inAuthenticDriverCount == 2)
            {
                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_9'),
                    'priority' => 'priority 9'
                ];

            }
            elseif ($inAuthenticDriverCount == 1)
            {
                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_10'),
                    'priority' => 'priority 10'
                ];

            }
            elseif ($pilotDriverCount == 2) {
                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_11'),
                    'priority' => 'priority 11'
                ];

            }
            elseif ($pilotDriverCount == 1) {

                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_12'),
                    'priority' => 'priority 12'
                ];

            }
            elseif ($countFirstRowDriver > 21) {

                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_13'),
                    'priority' => 'priority 13'
                ];

            }
            elseif ($countFirstRowDriver < 16) {

                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_14'),
                    'priority' => 'priority 14'
                ];

            }
            elseif (in_array($assessmentDetails['alchemy'], [700, 610, 601, 520, 511, 502, 430])) {

                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_15'),
                    'priority' => 'priority 15'
                ];

            }
            elseif (in_array($assessmentDetails['alchemy'], [223, 133, 043, 214, 124, 115, 034, 007])) {

                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_16'),
                    'priority' => 'priority 16'
                ];

            }
            elseif ($countGreaterThan12 >= 2) {

                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_17'),
                    'priority' => 'priority 17'
                ];

            }
            elseif (count(array_filter($values, function ($value) {
                    return $value > 12;
                })) == 1) {

                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_18'),
                    'priority' => 'priority 18'
                ];

            }
            elseif ($countLessThan7 == 2) {

                $actionPlan = config('actionPlan.priority_19');

            }
            elseif (count(array_filter($values, function ($value) {
                    return $value < 7;
                })) == 1) {

                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_20'),
                    'priority' => 'priority 20'
                ];

            }
            elseif ($assessmentDetails['firstRow']['pv'] == 0) {

                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_21'),
                    'priority' => 'priority 21'
                ];

            }
            elseif ($assessmentDetails['firstRow']['pv'] < 0) {

                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_22'),
                    'priority' => 'priority 22'
                ];

            }
            elseif ($assessmentDetails['firstRow']['pv'] > 12) {

                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_23'),
                    'priority' => 'priority 23'
                ];

            }
            elseif ($assessmentDetails['firstRow']['ep'] < 25) {


                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_24'),
                    'priority' => 'priority 24'
                ];

            }
            elseif ($assessmentDetails['firstRow']['ep'] > 35) {


                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_25'),
                    'priority' => 'priority 25'
                ];

            } else {


                $actionPlan = [
                    'plan_text' => config('actionPlan.priority_26'),
                    'priority' => 'priority 26'
                ];

            }

            $plan = self::create([
                'user_id' => $user['id'],
                'plan_text' => $actionPlan['plan_text'],
                'priority' => $actionPlan['priority'],
                'assessment_id' => $assessment['id'],
            ]);

            return $plan;

//        }


    }

    public static function userActionPlan($user = null)
    {

        if (!empty($user)) {

            $user_id = $user['id'];

        }else
        {
            $user_id = Helpers::getUser()->id ?? Helpers::getWebUser()->id;
        }

        return self::getUserActionPlan($user_id);

    }

    public static function getUserActionPlan($user_id = null)
    {

        return self::where('user_id', $user_id)->select(['id','priority', 'plan_text', 'text'])->latest()->first();
    }

    public static function deleteUserActionPlan($user_id = null)
    {
        $record = self::where('user_id', $user_id)->latest()->first();

        if ($record) {
            return $record->delete();
        }

        return false;
    }

}
