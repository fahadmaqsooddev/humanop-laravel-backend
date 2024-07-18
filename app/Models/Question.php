<?php

namespace App\Models;

use App\Helpers\Helpers;
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

    // relations
    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id');
    }

    public function subQuestionsForApi(){

        return $this->hasMany(Question::class,'question_id','id')->whereNotNull('question_id');
    }


    // query
    public static function allQuestion()
    {
        return self::whereNull('question_id')->with(['answers.answerCodes','subQuestions.answers']);
    }



    public static function totalAssessmentQuestion(){
        $question_ids =  self::whereIn('gender', [Auth::user()['gender'], 0])
            ->where('active', 1)->pluck('id');
        $main_questions = self::with('answers.answerCodes')
            ->whereNull('question_id')
            ->whereIn('id', $question_ids)
            ->whereIn('gender', [Auth::user()['gender'], 0])
            ->where('active', 1)
            ->count();
        return $main_questions;
    }
    public function subQuestions()
    {
        return $this->hasMany(Question::class, 'question_id');
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
            'multiple' => $question['multiple'],
            'question_id' => $question['id'],
        ]);

    }

    public static function paginatedQuestions($offset = 0, $limit = 3){

        $questions = self::whereIn('gender', [Helpers::getUser()->gender, 0])

            ->whereNull('question_id')

            ->where('active', 1)

            ->with(['subQuestionsForApi.answers.subAnswerCodes','answers.answerCodes'])

            ->orderBy('id',"ASC")

            ->paginate(3)->toArray();

        $final_questions = [];

        foreach ($questions['data'] as $key => $question){

            $temp_array = [];

            $sub_questions = $question['sub_questions_for_api'];

            unset($question['sub_questions_for_api']);

            array_push($temp_array, $question);

            foreach ($sub_questions as $sub_question){

                array_push($temp_array, $sub_question);

            }

            $final_questions[$key] = $temp_array[array_rand($temp_array)];

        }

        $questions['data'] = $final_questions;

        return $questions;

    }

}
