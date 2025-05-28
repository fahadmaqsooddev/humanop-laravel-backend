<?php

namespace App\Models\IntentionPlan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function intentionOptions()
    {
        return $this->belongsTo(IntentionOption::class,'intention_option_id', 'id');
    }

    public static function createIntentionPlan($userId = null, $intentionplans = null)
    {

        foreach ($intentionplans as $intention)
        {

            $intention_plan = self::create([
                'user_id' => $userId,
                'intention_option_id' => $intention,
            ]);
        }

        return $intention_plan;
    }

    public static function updateIntentionPlan($userId = null, $intentionPlan = [])
    {
        $plan = self::where('user_id', $userId)->first();

        if ($plan) {
            self::where('user_id', $userId)->delete();
        }

        DB::transaction(function() use ($intentionPlan,$userId){
                self::createIntentionPlan($userId, $intentionPlan);
        });

        return $intentionPlan;
    }

    public static function getUserIntentionPlan($user_id = null)
    {
        $intentions = self::where('user_id', $user_id)->with('intentionOptions', function ($q){

            return $q->select(['id','description']);

        })->get();

        $intentionOption = [];

        foreach ($intentions as $intention) {

            $intentionOption[] = $intention['intentionOptions']['description'];

        }

        return $intentionOption;
    }

}
