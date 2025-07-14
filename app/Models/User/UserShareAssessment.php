<?php

namespace App\Models\User;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserShareAssessment extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function getSingleRecord($user_id)
    {
        return self::where('user_id', $user_id)->first();
    }

    public static function createOrUpdateShareAssessment($shareAssessment = null)
    {

        $userId = Helpers::getUser()['id'];

        $getRecord = self::getSingleRecord($userId);

        if (empty($getRecord))
        {
            return self::create([
                'user_id' => $userId,
                'interval_of_life' => $shareAssessment['interval_of_life'],
                'traits' => $shareAssessment['traits'],
                'motivational_driver' => $shareAssessment['motivational_driver'],
                'alchemic_boundaries' => $shareAssessment['alchemic_boundaries'],
                'communication_style' => $shareAssessment['communication_style'],
                'perception_of_life' => $shareAssessment['perception_of_life'],
                'energy_pool' => $shareAssessment['energy_pool'],
            ]);
        }
        else
        {
            self::where('user_id', $userId)->update($shareAssessment);
        }

    }

}
