<?php

namespace App\Models\Admin\Faq;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqModel extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function storeFaq($question=null,$answer=null)
    {
       return self::create([
            'question' => $question,
            'answer' => $answer
        ]);
    }
    public static function allFaqsQuestions($request=null){
        $orderBy = $request->orderBy ?? 'id';
        $order = $request->order ?? 'ASC';

        $faqs = self::query();

        $faqs = $faqs->when($request && $request->input('search'), function ($query) use ($request) {
            $query->where('question', 'like', '%' . $request->input('search') . '%');
        });


        return $faqs->orderBy($orderBy, $order)->get();

    }

    public static function findQuestion($id=null){
        return self::find($id);
    }

    public static function updateQuestions($id=null,$question=null,$answer=null){
        self::whereId($id)->update([
            'question' => $question,
            'answer' => $answer
        ]);
    }

    public static function deleteQuestion($id=null)
    {
        return self::destroy($id);
    }
}
