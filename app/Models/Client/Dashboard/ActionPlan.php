<?php

namespace App\Models\Client\Dashboard;

use App\Enums\Admin\Admin;
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

    public static function checkUserActionPlan($assessment = null, $user = null)
    {
        if (!empty($assessment)) {

            if (!empty($user)) {

                $getUser = $user;

            } else {

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

    public static function storeUserActionPlan($assessment = null, $userPlan = null)
    {

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

        if ($userPlan == null || $userPlan == 'Freemium') {

            $actionPlan = Helpers::fourteenDaysActionPlan($assessmentDetails, $authenticTraitCount, $inAuthenticDriverCount, $pilotDriverCount, $countFirstRowDriver, $countGreaterThan12, $countLessThan7, $values);

        } else {

            $actionPlan = Helpers::NinetyDaysActionPlan($assessmentDetails, $authenticTraitCount, $inAuthenticDriverCount, $pilotDriverCount, $countFirstRowDriver, $countGreaterThan12, $countLessThan7, $values);

        }

        $plan = self::create([
            'user_id' => $assessment['user_id'],
            'plan_text' => $actionPlan['plan_text'],
            'priority' => $actionPlan['priority'],
            'assessment_id' => $assessment['id'],
            'type' => $userPlan == null || $userPlan == 'Freemium' ? Admin::FOURTEEN_DAYS_ACTION_PLAN : Admin::NINETY_DAYS_ACTION_PLAN,
        ]);

        return $plan;


    }

    public static function userActionPlan($user = null)
    {

        if (!empty($user)) {

            $user_id = $user['id'];

        } else {
            $user_id = Helpers::getUser()->id ?? Helpers::getWebUser()->id;
        }

        return self::getUserActionPlan($user_id);

    }

    public static function getUserActionPlan($user_id = null)
    {

        return self::where('user_id', $user_id)->select(['id', 'priority', 'plan_text', 'text'])->latest()->first();
    }

    public static function getActionPlanByAssessmentId($assessment = null, $userPlan = null)
    {

        $plan = $userPlan == null || $userPlan == 'Freemium' ? Admin::FOURTEEN_DAYS_ACTION_PLAN : Admin::NINETY_DAYS_ACTION_PLAN;

        return self::where('assessment_id', $assessment['id'])->where('type', $plan)->select(['id', 'type', 'priority', 'plan_text', 'text'])->latest()->first();

    }


}
