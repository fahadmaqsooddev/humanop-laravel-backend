<?php

namespace App\Models\IntentionPlan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class IntentionOption extends Model
{
    use HasFactory;
    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

     public static function getOptions(){
         return self::select(['id','description'])->get();
     }
    public static function allOptions(){
         return self::orderBy('created_at', 'desc');
    }

    public static function createPlanOption($data = null)
    {
        return self::create($data);
    }

    public static function updateIntentionPlan($data = null, $id = null)
    {

        $intention_plan = self::find($id);

        $intention_plan->update($data);

        return $intention_plan;
    }
}
