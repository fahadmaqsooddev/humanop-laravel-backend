<?php

namespace App\Models;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentDetail extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function createAssessmentDetail($data = null)
    {
        return self::create($data);
    }

    // public static function updateAssessmentDetail($data = null,$id = null){
    //     return self::find($id)->update($data);
    // }

    public static function getDetail($id = null)
    {
        return self::where('assessment_id', $id)->get();
    }

    public static function assessmentAnswers($request = null, $assessment_id = null)
    {

        $assessment_answers = self::where('assessment_id', $assessment_id)->select(['id', 'assessment_id', 'question', 'answer']);

        return Helpers::pagination($assessment_answers, $request->input('pagination'), $request->input('per_page'));

    }
}
