<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Answer\Answer;

class Question extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id');
    }

    public static function allQuestion()
    {
        return self::with('answers.answerCodes');
    }

    public static function getQuestion($offset = 0, $limit = 3)
    {
        $question_ids = self::whereIn('gender', [Auth::user()['gender'], 0])
            ->where('active', 1)
            ->offset($offset)
            ->limit($limit)
            ->pluck('id');

        $main_questions = self::with('answers.answerCodes')
            ->whereNull('question_id')
            ->whereIn('id', $question_ids)
            ->whereIn('gender', [Auth::user()['gender'], 0])
            ->where('active', 1)
            ->get()
            ->toArray();

        $sub_questions = self::with('answers.subAnswerCodes')
            ->whereIn('question_id', $question_ids)
            ->whereIn('gender', [Auth::user()['gender'], 0])
            ->where('active', 1)
            ->get()
            ->groupBy('question_id')
            ->toArray();

        $questions = [];

        foreach ($main_questions as $key => $main_question) {
            $questions[$key] = [$main_question];
            foreach ($sub_questions[$main_question['id']] ?? [] as $sub_question) {
                array_push($questions[$key], $sub_question);
            }
        }

        $q = [];
        foreach ($questions as $index => $questionArray) {
            $randomKey = array_rand($questionArray);
            $q[] = $questionArray[$randomKey];
        }

//        dd($q);

        return $q;
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

    public static function createQuestion($question = null, $sub_question = null)
    {
        return self::create([
            'question' => $sub_question,
            'active' => 1,
            'gender' => $question['gender'],
            'sort' => $question['sort'],
            'question_id' => $question['id'],
        ]);

    }
}
