<?php

namespace App\Models\CompatibilityReferenceKeys;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraitCompatibilityReferenceKeys extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function getCompatabilityScore($firstAssessment = null, $secondAssessment = null)
    {
        $firstAssessmentKeys = array_keys($firstAssessment);
        $secondAssessmentKeys = array_keys($secondAssessment);

        return [
            'first_score'  => self::where('first_reference_key', $firstAssessmentKeys[0])->where('second_reference_key', $secondAssessmentKeys[0])->value('volume'),
            'second_score' => self::where('first_reference_key', $firstAssessmentKeys[1])->where('second_reference_key', $secondAssessmentKeys[1])->value('volume'),
            'third_score'  => self::where('first_reference_key', $firstAssessmentKeys[2])->where('second_reference_key', $secondAssessmentKeys[2])->value('volume'),
        ];
    }
}
