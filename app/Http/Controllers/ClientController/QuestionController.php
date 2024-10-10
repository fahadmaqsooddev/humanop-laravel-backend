<?php

namespace App\Http\Controllers\ClientController;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Assessment;
use App\Helpers\Helpers;

class QuestionController extends Controller
{

    protected $question = null;

    public function __construct(Question $question)
    {
        $this->question = $question;
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

            return view('client-dashboard.question.assessment', compact('questions'));
//            }

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public static function allAssessment()
    {
        try {

            $assessments = Assessment::getAssessment();

            return view('client-dashboard.assessment.index', compact('assessments'));

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function introAssessment()
    {
        try {

            $user = Helpers::getWebUser();

            $assessment = Assessment::singleAssessment($user['id']);

            if ($assessment && $assessment['page'] == 0)
            {
                Assessment::createAssessmentData($user['id'], 0);
            }

            return view('client-dashboard.assessment.assessment-intro');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }
}
