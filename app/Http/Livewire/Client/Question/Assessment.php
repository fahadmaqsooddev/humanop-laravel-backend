<?php

namespace App\Http\Livewire\Client\Question;

use App\Models\Question;
use Livewire\Component;
use App\Models\Assessment as AssessmentModal;
use Illuminate\Support\Facades\Auth;
class Assessment extends Component
{
    public $offset = 0;
    public $limit = 3;
    public $answers = []; // Use an array to store answers
    public $questions;

    public function updateAssessment()
    {
        try{
            $userId = Auth::user()->id;
            $codeArray = [];
            ksort($this->answers);
            foreach ($this->answers as $item) {
                foreach ($item['answer_codes'] as $code => $value) {
                    $lowercaseCode = strtolower($code);
                    if (!isset($codeArray[$lowercaseCode])) {
                        $codeArray[$lowercaseCode] = 0;
                    }
                    $codeArray[$lowercaseCode] += $value;
                }
            }
            $this->updateQuestion();
            $this->offset += 3;

            $existingAssessment = AssessmentModal::where('user_id', $userId)->first();

            if ($existingAssessment) {

                    $oldResult = $existingAssessment->toArray();
                    $resultArray = [];

                foreach ($codeArray as $key => $value) {
                    $resultArray[$key] = $value;
                }

                foreach ($oldResult as $key => $value) {
                    if (isset($resultArray[$key])) {
                        $resultArray[$key] += $value;
                    } else {
                        $resultArray[$key] = $value;
                    }
                }
                    $resultArray['page'] = $this->offset / 3;
                    $existingAssessment->update($resultArray);
            } else {

                $finalAssessment= array_merge(['user_id' => $userId,'page' =>   $this->offset / 3], $codeArray);
                AssessmentModal::create($finalAssessment);
            }
        }catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
    }

    public function updateQuestion()
    {
        $this->questions = Question::getQuestion($this->offset, $this->limit);
    }

    public function selectAnswer($questionId, $answer,$answerCodes)
    {
        $codes = [];
        $codeArr = json_decode($answerCodes, true);
        foreach ($codeArr as $code){
            $codes[$code['code']] = $code['number'];
        }
        $this->answers[$questionId] = ['answer_id' => $answer,'answer_codes' => $codes];
    }

    public function render()
    {
        $this->updateQuestion();
        return view('livewire.client.question.assessment', ['questions' => $this->questions]);
    }
}
