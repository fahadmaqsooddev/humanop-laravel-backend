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


    public static function storeUserIntentions($userId, $intentionIds)
    {

        foreach ($intentionIds as $intentionId) {
            self::create([
                'business_id' => $userId,
                'intention_option_id' => $intentionId
            ]);
        }
    }



}
