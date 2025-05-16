<?php

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function getSingleNotification($userId = null)
    {
        return self::where('user_id', $userId)->first();
    }

    public static function createNotification($userId = null)
    {
        return self::create(['user_id' => $userId]);
    }

    public static function changeNotification($userId = null, $notification = null)
    {
        $changeNotification = self::where('user_id', $userId)->first();

        if ($notification == 'optimal_trait') {

            if ($changeNotification['optimal_trait'] == 1) {

                return $changeNotification->update(['optimal_trait' => 0]);

            } else {

                return $changeNotification->update(['optimal_trait' => 1]);

            }

        } elseif ($notification == 'daily_tip') {

            if ($changeNotification['daily_tip'] == 1) {

                return $changeNotification->update(['daily_tip' => 0]);

            } else {

                return $changeNotification->update(['daily_tip' => 1]);

            }

        } elseif ($notification == 'reset_assessment') {

            if ($changeNotification['reset_assessment'] == 1) {

                return $changeNotification->update(['reset_assessment' => 0]);

            } else {

                return $changeNotification->update(['reset_assessment' => 1]);

            }

        } else {

            if ($changeNotification['resource'] == 1) {

                $changeNotification->update(['resource' => 0]);

            } else {
                $changeNotification->update(['resource' => 1]);

            }

        }

    }

}
