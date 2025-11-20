<?php

namespace App\Models;

use App\Helpers\Helpers;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public function getCreatedAtAttribute($value)
    {

        $user = User::getSingleUser($this->subject_id);
        $timezone = $user->timezone ?? 'UTC';

        $formattedTimestamp = str_replace('T', ' ', $value);

        $formattedTimestamp = explode('.', $formattedTimestamp)[0];

        $minutes = Helpers::explodeTimezoneWithHoursAndMinutes($timezone);

        return Carbon::parse($formattedTimestamp)->addMinutes($minutes)->format('m/d/Y h:i A');
    }


    public static function getLogs($userId = null, $perPage = 10)
    {
        return self::where('subject_id', $userId)->latest('created_at')->paginate($perPage);
    }

}
