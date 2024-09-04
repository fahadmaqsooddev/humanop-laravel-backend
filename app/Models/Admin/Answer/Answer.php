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

    protected $appends = ['image_url'];

    // appends
    public function getImageUrlAttribute(){

        return $this->image != "NULL" ? asset('/images/q/' . $this->image) : null;
    }


    // relations
    public function answerCodes()
    {
        return $this->hasMany(AnswerCode::class, 'answer_id', 'id');
    }

    public function subAnswerCodes()
    {
        return $this->hasMany(AnswerCode::class, 'answer_id', 'answer_id');
    }

    public static function getAnswer($id = null)
    {
        return self::where('question_id', $id)->get();
    }

    public static function singleAnswer($id = null)
    {
        return self::whereId($id)->first();
    }

    public static function updateAnswer($data = null,$id = null)
    {
        if($id){
            $ans = self::find($id);
            $ans->update($data);
        }else{
            foreach ($data as $answer) {

                $ans = self::find($answer['id']);

                $ans->update($answer);

            }
        }


        return 1;
    }

    public static function createAnswer($answers = null, $sub_answers = null, $id = null)
    {

        foreach ($answers as $index => $answer) {

            if (isset($sub_answers[$index])) {
                self::create([
                    'question_id' => $id,
                    'answer' => $sub_answers[$index],
                    'sort' => $answer['sort'],
                    'image' => $answer['image'],
                    'answer_id' => $answer['id'],
                ]);
            } else {

                return false;
            }

        }

        return true;

    }
}
