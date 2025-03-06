<?php

namespace App\Models\Admin\FineTuneContent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FineTuneContent extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    // Queries
    public static function addFineTuneContent($question, $answer){

        self::create([
            'question' => $question,
            'answer' => $answer,
        ]);

    }

    public static function allContent($perPage){

        return self::where('is_fine_tuned', 0)->paginate($perPage);

    }

    public static function updateFineTunedContent($id, $question, $answer){

        self::whereId($id)->update([
            'question' => $question,
            'answer' => $answer,
        ]);
    }

    public static function addLisaApprovedQuestionAnswers($data){

        self::create($data);

    }
}
