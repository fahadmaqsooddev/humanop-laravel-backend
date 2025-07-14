<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Question;

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

            return view('admin-dashboards.questions.index', compact('questions'));

        } catch (\Exception $exception) {

            return redirect()->route('admin_all_questions')->with('error', $exception->getMessage());

        }
    }

    public function editQuestions($id)
    {

        try {

            $question = Question::singleQuestion($id);

            return view('admin-dashboards.edit_question', compact('question'));

        } catch (\Exception $exception) {

            return redirect()->route('admin_all_questions')->with('error', $exception->getMessage());

        }

    }

}
