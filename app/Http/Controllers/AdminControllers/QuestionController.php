<?php

namespace App\Http\Controllers\AdminControllers;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Question;
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

            return view('admin-dashboards.questions.index', compact('questions'));

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_all_questions')->with('error', $exception->getMessage());

        }
    }

    public function editQuestions($id)
    {

        try {

            $question = Question::singleQuestion($id);

            return view('admin-dashboards.edit_question', compact('question'));

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_all_questions')->with('error', $exception->getMessage());

        }

    }

    public function introAssessment()
    {
        try {
            $user = Helpers::getWebUser();

            $assessment = Assessment::singleAssessment($user['id']);

            if (!$assessment || $assessment['page'] === 0) {

                Assessment::createAssessmentData($user['id'], 0);
            }

            return view('practitioner-dashboard.assessment.assessment-intro');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function testPlay()
    {
        try {

            $user = Helpers::getWebUser();


//            $assessmentCheck = Helpers::checkAssessment($user['id']);

//            if($assessmentCheck == true)
//            {
//                return redirect()->route('stripe_checkout');
//            }else
//            {

            $questions = Question::getQuestion();

            return view('practitioner-dashboard.question.assessment', compact('questions'));
//            }

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

}
