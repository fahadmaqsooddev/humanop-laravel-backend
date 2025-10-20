<?php

namespace App\Models\Admin\Plan;

use App\Enums\Admin\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptimizationPlan extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function getSinglePlan($priority = null, $userPlan = null)
    {
        $plan = $userPlan != "premium" ? 1 : 2;

        return self::where('priority', $priority)->where('type', $plan)->latest()->first();
    }

    public static function allOptimizationPlans()
    {
        return self::orderBy('created_at', 'desc');
    }

    public static function fourteenDaysOptimizationPlans()
    {
        return self::where('type', Admin::FOURTEEN_DAYS_ACTION_PLAN)->orderBy('created_at', 'desc');
    }

    public static function nintyDaysOptimizationPlans()
    {
        return self::where('type', Admin::NINETY_DAYS_ACTION_PLAN)->orderBy('created_at', 'desc');
    }

    public static function updateOptimizationPlan($priority = null, $fourteen_days_plan = null)
    {
        return self::where('priority', $priority)->update(['fourteen_days_plan' => $fourteen_days_plan]);
    }

    public static function updateNintyDaysOptimizationPlan($priority = null, $ninty_days_plan = null, $day1_30 = null, $day31_60 = null, $day61_90 = null)
    {
        return self::where('priority', $priority)
            ->update([
                'ninty_days_plan' => $ninty_days_plan,
                'day1_30' => $day1_30,
                'day31_60' => $day31_60,
                'day61_90' => $day61_90,
            ]);
    }

}
