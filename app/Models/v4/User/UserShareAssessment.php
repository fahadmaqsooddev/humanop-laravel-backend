<?php

namespace App\Models\v4\User;

use App\Helpers\v4\Helpers;
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
        return self::where('user_id', $user_id)->select('user_id', 'authentic_traits', 'core_state')->first();
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
            ]);
        }
        else
        {
            self::where('user_id', $userId)->update($shareAssessment);
        }

    }

}
