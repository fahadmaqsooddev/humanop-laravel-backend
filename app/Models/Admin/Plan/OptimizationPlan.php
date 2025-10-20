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
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    public static function allOptimizationPlans()
    {
        return self::orderBy('created_at', 'desc');
    }

    public static function fourteenDaysOptimizationPlans()
    {
        return self::where('type', Admin::FOURTEEN_DAYS_ACTION_PLAN)->orderBy('created_at', 'desc');
    }

    public static function updateOptimizationPlan($priority = null, $fourteen_days_plan = null)
    {
        return self::where('priority', $priority)->update(['fourteen_days_plan' => $fourteen_days_plan]);
    }

}
