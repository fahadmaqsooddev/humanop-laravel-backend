<?php

namespace App\Models\IntentionPlan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntentionPlan extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function createIntentionPlan($userId = null, $plan = null)
    {
        return self::create([
            'user_id' => $userId,
            '90_day_intention' => $plan,
        ]);
    }

    public static function getIntentionPlan($userId = null)
    {
        return self::where('user_id', $userId)->first('90_day_intention');
    }

    public static function updateIntentionPlan($userId = null, $intentionPlan = null)
    {
        $plan = self::where('user_id', $userId)->first();

        if ($plan) {
            $plan->update(['90_day_intention' => $intentionPlan]);
        } else {

            $plan = self::createIntentionPlan($userId, $intentionPlan);
        }

        return $plan;
    }

}
