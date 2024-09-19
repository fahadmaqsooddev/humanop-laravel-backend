<?php

namespace App\Models\Client\Plan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    public static function singlePlan($plan_id = null){

        return self::where('plan_id', $plan_id)->first();
    }

    public static function allPlans(){

        $plans = self::all();

        foreach ($plans as $plan){

            if ($plan['name'] === 'Freemium'){

                $plan['limitations'] = [
                    '1 Assessment every 90 days',
                    'Daily Tip',
                    '1 Action Item',
                    'Basic Results',
                    'Action Plan',
                    'Training Strategies',
                    'Renewal System',
                    'Early Releases'
                ];

            }elseif ($plan['name'] === 'Core'){

                $plan['limitations'] = [
                    '1 Assessment every 90 days',
                    'Multiple Tips',
                    '3 Action Items',
                    'Detailed Results',
                    'Action Plan',
                    'Training Strategies',
                    'Renewal System',
                    'Early Releases'
                ];

            }elseif ($plan['name'] === 'Premium'){

                $plan['limitations'] = [
                    'Licensing Model',
                    'Multiple Daily Tips',
                    'HAI Feature',
                    'Gamification',
                    'Training Strategies',
                    'Renewal System',
                    'Action Plan',
                    'Early Releases'
                ];

            }

        }

        return $plans;

    }

    public static function findPlanFromIntValue($int_value = null){

        $plan_name = $int_value === 0 || $int_value === 1 ? $int_value === 1 ? "Core" : "Freemium" : "Premium";

        return self::where('name', $plan_name)->first();
    }

}
