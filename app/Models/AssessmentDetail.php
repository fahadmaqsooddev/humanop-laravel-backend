<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentDetail extends Model
{
    use HasFactory;
    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');
        parent::__construct($attributes);
    }

    public static function createAssessmentDetail($data = null){
        return self::create($data);
    }

    public static function updateAssessmentDetail($data = null,$id = null){
        return self::find($id)->update($data);
    }

    public static function getDetail($id = null)
    {
        return self::where('assessment_id', $id)->get();
    }
}
