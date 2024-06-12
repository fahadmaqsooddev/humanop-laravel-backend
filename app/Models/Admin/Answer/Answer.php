<?php

namespace App\Models\Admin\Answer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\AnswerCode\AnswerCode;

class Answer extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public function answerCodes()
    {
        return $this->hasMany(AnswerCode::class, 'answer_id');
    }

    public static function getAnswer($id = null)
    {
        return self::where('question_id', $id)->get();
    }

    public static function updateAnswer($data = null)
    {

        foreach ($data as $answer) {

            $ans = self::find($answer['id']);

            $ans->update($answer);

        }

        return 1;
    }

    public static function createAnswer($answers = null, $sub_answers = null, $id = null)
    {

        foreach ($sub_answers as $index => $answer) {

            if (isset($answers[$index])) {

                self::create([
                    'question_id' => $id,
                    'answer' => $answer,
                    'sort' => $answers[$index]['sort'],
                    'image' => $answers[$index]['image'],
                    'answer_id' => $answers[$index]['id'],
                ]);

            } else {

                return false;
            }

        }

        return true;

    }
}
