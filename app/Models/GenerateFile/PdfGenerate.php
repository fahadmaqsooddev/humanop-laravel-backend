<?php

namespace App\Models\GenerateFile;

use App\Models\Admin\Code\CodeDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdfGenerate extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public function codeDetails()
    {
        return $this->hasMany(CodeDetail::class, 'id', 'code_detail_id');
    }

    public static function getPdfContent($assessmentId = null, $userId = null)
    {
        return self::where('assessment_id', $assessmentId)
            ->where('user_id', $userId)
            ->with('codeDetails')
            ->get();
    }

    public static function createGenerateFile($assessmentId = null, $userId = null, $styleCodeDetails = null, $getStyle = null)
    {
        $pdfFile = self::getPdfContent($assessmentId, $userId);

        if (empty($pdfFile->toArray())) {

            foreach ($styleCodeDetails as $codeDetail) {

                foreach ($getStyle as $index => $styleNumber)
                {
                    $key = strtoupper($index);

                    if ($key == $codeDetail['code'])
                    {

                        self::create([
                            'assessment_id' => $assessmentId,
                            'user_id' => $userId,
                            'code_detail_id' => $codeDetail['id'],
                            'code_number' => $styleNumber,
                        ]);
                    }
                }

            }

            $getPdfFile = self::getPdfContent($assessmentId, $userId);

        } else {

            $getPdfFile = $pdfFile;
        }

        return $getPdfFile;
    }

}
