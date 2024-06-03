<?php

namespace App\Models\Admin\Question;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\Answer\Answer;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class,'question_id');
    }

    public static function allQuestion()
    {
        return self::with('answers.answerCodes')->get();
    }

    public static function singleQuestion($id = null)
    {
        return self::with('answers')->find($id);
    }

    public static function updateQuestion($data = null, $id = null)
    {

        $question = self::find($id);

        $question->update($data);

        return $question;

    }
}
