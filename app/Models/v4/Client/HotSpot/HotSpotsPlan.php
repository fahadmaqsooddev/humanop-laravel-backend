<?php

namespace App\Models\v4\Client\HotSpot;

use App\Helpers\Helpers;
use App\Models\Assessment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotSpotsPlan extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }


    public static function getActionPlanByAssessmentId($assessment = null, $userPlan = null)
    {

        return self::where('assessment_id', $assessment['id'])->select(['id', 'priority', 'hot_spots_text'])->latest()->first();

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

        $actionPlan = Helpers::ninetyDaysHotSpots($assessmentDetails, $authenticTraitCount, $inAuthenticDriverCount, $pilotDriverCount, $countFirstRowDriver, $countGreaterThan12, $countLessThan7, $values);


        $plan = self::create([
            'user_id' => $assessment['user_id'],
            'hot_spots_text' => $actionPlan['plan_text'],
            'priority' => $actionPlan['priority'],
            'assessment_id' => $assessment['id'],
        ]);

        return $plan;

    }
}
