<?php

namespace App\Models\Client\Plan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Admin\Admin;

class Plan extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function singlePlan($plan_id = null)
    {

        return self::where('plan_id', $plan_id)->first();
    }

    public static function allPlans()
    {

        $plans = self::all();

        foreach ($plans as $plan) {

            if ($plan['name'] === 'Freemium') {

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

            } elseif ($plan['name'] === 'Premium') {

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

            }
//            elseif ($plan['name'] === 'Premium') {
//
//                $plan['limitations'] = [
//                    'Licensing Model',
//                    'Multiple Daily Tips',
//                    'HAI Feature',
//                    'Gamification',
//                    'Training Strategies',
//                    'Renewal System',
//                    'Action Plan',
//                    'Early Releases'
//                ];
//
//            }

        }

        return $plans;

    }

    public static function findPlanFromIntValue($int_value = null)
    {

        $plan_name = $int_value === 0 || $int_value === 1 ? $int_value === 1 ? "Premium" : "Freemium" : "Premium";

        return self::where('name', $plan_name)->first();
    }

    public static function planNames()
    {

        return self::select(['id', 'name'])->get();
    }

    public static function storePlan($data = null)
    {
        return self::create($data);
    }

    public static function getB2BPlans()
    {
        return self::where('plan_type', Admin::B2B_PLAN)->get();
    }

    public static function getB2CPlans()
    {
        return self::where('plan_type', Admin::B2C_PLAN)->get();
    }

    public static function getB2BActivePlans()
    {
        return self::where('plan_type', 1)->where('status', Admin::B2B_ACTIVE_PLAN)->get();
    }

    public static function getdashboadB2Bplans($select = null)
    {
        $plans = self::where('plan_type', Admin::B2B_PLAN);
        if (!empty($select)) {
            $plans->where('billing_method', $select);
        }

        return $plans->get();
    }

    public static function getSingleB2BPlan($id = null)
    {
        return self::where('id', $id)->first();
    }

//    public static function activeb2BPlans()
//    {
//        return self::where('status', Admin::B2B_ACTIVE_PLAN)->count();
//    }
}
