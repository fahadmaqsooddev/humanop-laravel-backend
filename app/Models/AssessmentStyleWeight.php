<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentStyleWeight extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        parent::__construct($attributes);
    }

    public static function getStyleWeight($assessmentId = null)
    {

        return self::where('assessment_id', $assessmentId)->first();

    }

    public static function createStyleWeights($assessmentId = null, $styleWeights = null)
    {

        $getStyleWeight = self::getStyleWeight($assessmentId);

        if (!empty($getStyleWeight)) {

            $styleWeight = self::updateStyleWeight($assessmentId, $styleWeights);

        } else {

            self::create([
                'assessment_id' => $assessmentId,
                'sa_weight' => $styleWeights['sa'],
                'ma_weight' => $styleWeights['ma'],
                'jo_weight' => $styleWeights['jo'],
                'lu_weight' => $styleWeights['lu'],
                'ven_weight' => $styleWeights['ven'],
                'mer_weight' => $styleWeights['mer']
            ]);

        }

        return $styleWeight;

    }

    public static function updateStyleWeight($assessmentId = null, $styleWeights = null)
    {
        return self::where('assessment_id', $assessmentId)->update([
            'sa_weight' => $styleWeights['sa'],
            'ma_weight' => $styleWeights['ma'],
            'jo_weight' => $styleWeights['jo'],
            'lu_weight' => $styleWeights['lu'],
            'ven_weight' => $styleWeights['ven'],
            'mer_weight' => $styleWeights['mer']
        ]);
    }
}
