<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function insertData($assessmentId, $data, ?int $userId = null, ?string $dateOfBirth = null)
    {
        $authUser = Helpers::getUser();
        $resolvedUserId = $userId ?? $authUser?->id;
        $resolvedDateOfBirth = $dateOfBirth ?? $authUser?->date_of_birth;

        if (empty($resolvedUserId) || empty($resolvedDateOfBirth)) {
            return;
        }

        $intervalData = User::userIntervalOfLife($resolvedDateOfBirth);
        $shiftInterval = $intervalData['interval'] ?? 'Unknown Interval';

        $activeHotspots = Helpers::getActiveHotspots($data);
        if (empty($activeHotspots)) {
            return;
        }

        $now = Carbon::now();
        $rows = [];

        foreach ($activeHotspots as $hotspot) {
            $rows[] = [
                'user_id' => $resolvedUserId,
                'assessment_id' => $assessmentId,
                'hotspot_id' => $hotspot['id'] ?? null,
                'hotspot_score' => $hotspot['id'] ?? 0,
                'names' => $hotspot['name'] ?? null,
                'shift_interval' => $shiftInterval,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        self::insert($rows);
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
