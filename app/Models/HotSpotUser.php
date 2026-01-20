<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Helpers\Helpers;
use Carbon\Carbon;

class HotSpotUser extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        parent::__construct($attributes);
    }

    public function insertData($assessmentId, $data){
        $user = Helpers::getUser();
        $intervalData = User::userIntervalOfLife($user->date_of_birth);
        $shiftInterval = $intervalData['interval'] ?? 'Unknown Interval';
        $activeHotspots = Helpers::getActiveHotspots($data,$assessmentId,$user->date_of_birth);
        if($activeHotspots){
           foreach($activeHotspots as $hotspot){
                self::create([
                    'user_id' => $user->id,
                    'assessment_id' => $assessmentId,
                    'hotspot_id' => $hotspot['id'],
                    'hotspot_score' => $hotspot['id'],
                    'names' => $hotspot['name'],
                    'shift_interval' => $shiftInterval
                ]);
            }
        }
    }

     /**
     * Get the latest assessment ID for a given user.
     */
    public static function getLatestAssessmentId($userId)
    {
        return self::where('user_id', $userId)->max('assessment_id');
    }

    /**
     * Get the previous assessment ID for a given user.
     */
    public static function getPreviousAssessmentId($userId, $currentAssessmentId)
    {
        return self::where('user_id', $userId)
            ->where('assessment_id', '<', $currentAssessmentId)
            ->max('assessment_id');
    }

     /**
     * Fetch rows for a user and assessment.
     */
    public static function getRows($userId, $assessmentId)
    {
        return self::where('user_id', $userId)
            ->where('assessment_id', $assessmentId)
            ->get();
    }
}
