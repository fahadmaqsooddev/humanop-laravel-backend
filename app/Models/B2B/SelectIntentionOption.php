<?php

namespace App\Models\B2B;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectIntentionOption extends Model
{
    use HasFactory;
    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    // relations
    public function intentionOptions()
    {
        return $this->belongsTo(B2BIntentionOption::class,'intention_option_id', 'id');
    }

    // query
    public static function selectB2BIntentionOption($user_id){

        $intentions = self::where('business_id', $user_id)->with('intentionOptions', function ($q){

            return $q->select(['id','intention_option']);

        })->get();

        $intentionOption = [];

        foreach ($intentions as $intention) {

            $intentionOption[] = $intention['intentionOptions']['intention_option'];

        }

        return $intentionOption;

    }
}
