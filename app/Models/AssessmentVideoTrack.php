<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentVideoTrack extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function getAssessmentVideoTrack($user_id = null)
    {
        return self::select('assessment_id', 'user_id', 'video_name', 'video_time')->where('user_id', $user_id)->get();
    }

    public static function createOrUpdateAssessmentVideoTrack($data = [])
    {
        return self::updateOrCreate(
            [
                'assessment_id' => $data['assessment_id'],
                'user_id' => $data['user_id']
            ], $data->all());
    }
}
