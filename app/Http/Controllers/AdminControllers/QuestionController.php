<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Question\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{

    protected $question = null;

    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    public function allQuestions()
    {
        try {

            $questions = Question::allQuestion();
//            dd($questions);

            return view('admin-dashboards.all_questions', compact('questions'));

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_all_questions')->with('error', $exception->getMessage());

        }
    }
}
