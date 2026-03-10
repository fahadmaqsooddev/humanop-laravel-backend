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
        return self::where('user_id', $user_id)->select(
            'user_id',
            'authentic_traits',
            'core_state',
            'interval_of_life',
            'traits',
            'motivational_driver',
            'alchemic_boundaries',
            'communication_style',
            'perception_of_life',
            'energy_pool'
        )->first();
    }

    public static function createOrUpdateShareAssessment($shareAssessment = null)
    {

        $userId = Helpers::getUser()['id'];

        $getRecord = self::getSingleRecord($userId);

        if (empty($getRecord))
        {
            return self::create([
                'user_id' => $userId,
                'authentic_traits' => $shareAssessment['authentic_traits'],
                'core_state' => $shareAssessment['core_state'],
                'interval_of_life' => $shareAssessment['interval_of_life'] ?? null,
                'traits' => $shareAssessment['traits'] ?? null,
                'motivational_driver' => $shareAssessment['motivational_driver'] ?? null,
                'alchemic_boundaries' => $shareAssessment['alchemic_boundaries'] ?? null,
                'communication_style' => $shareAssessment['communication_style'] ?? null,
                'perception_of_life' => $shareAssessment['perception_of_life'] ?? null,
                'energy_pool' => $shareAssessment['energy_pool'] ?? null,
            ]);
        }
        else
        {
            self::where('user_id', $userId)->update($shareAssessment);
        }

    }

}
