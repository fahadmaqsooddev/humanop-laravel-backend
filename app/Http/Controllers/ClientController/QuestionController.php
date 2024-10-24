<?php

namespace App\Http\Controllers\ClientController;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Assessment;
use App\Helpers\Helpers;
use App\Models\User;
use Faker\Extension\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

            if (!empty($user['timezone'])) {

                $assessment = Assessment::singleAssessment($user['id']);

                if (!$assessment || $assessment['page'] === 0) {

                    $minutes = Helpers::explodeTimezoneWithHours($user['timezone']);

                    $userTime = \Carbon\Carbon::parse($assessment['updated_at'])
                        ->addMinutes($minutes * 60)
                        ->toDateTimeString();

                    $difference = \Carbon\Carbon::now()->diffInDays($userTime);

                    if ($difference > 90) {

                        Assessment::createAssessmentData($user['id'], 0);

                        $timezones = Helpers::timeZone();

                        return view('client-dashboard.assessment.assessment-intro', compact('timezones'));
                    } else {

                        $takeAssessment = 90 - $difference;

                        return redirect()->route('client_dashboard')->with('error', 'You can take another assessment after ' . $takeAssessment . ' days.');
                    }
                }
                else
                {
                    $timezones = Helpers::timeZone();

                    return view('client-dashboard.assessment.assessment-intro', compact('timezones'));
                }
            }
            else
            {

                $timezones = Helpers::timeZone();

                return view('client-dashboard.assessment.assessment-intro', compact('timezones'));

            }

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());
        }
    }


    public function setTimezone(Request $request)
    {
        try {

            User::updateUserTimezone($request['timezone']);

            return redirect()->back()->with('success', 'Timezone Successfully updated');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

}
