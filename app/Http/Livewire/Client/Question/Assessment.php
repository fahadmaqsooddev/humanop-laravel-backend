<?php

namespace App\Http\Livewire\Client\Question;

use App\Models\AssessmentDetail;
use App\Models\Question;
use Livewire\Component;
use App\Models\Assessment as AssessmentModal;
use Illuminate\Support\Facades\Auth;

class Assessment extends Component
{
    public $offset = 0;
    public $limit = 3;
    public $answers = [];
    public $questions;
    public $currentPage = 0;
    public $totalQuestion = 0;
    public $assessmentId = 0;
    public function mount(){
        $this->totalQuestion = Question::totalAssessmentQuestion();
        $this->currentPage = AssessmentModal::getLastPage();
        $this->offset = $this->currentPage * 3;
    }
    public function updateAssessment()
    {
        if(count($this->questions) != count($this->answers)){
             $this->emit('scrollToTop');
             $this->skipRender();
             return;
         }
         try {

             $totalPages = ceil($this->totalQuestion / $this->limit);


            $userId = Auth::user()->id;


            $codeArray = [];


            ksort($this->answers);


            foreach ($this->answers as $item) {
                foreach ($item['answer_codes'] as $code => $value) {
                    $lowercaseCode = strtolower($code);


                    if (!isset($codeArray[$lowercaseCode])) {
                        $codeArray[$lowercaseCode] = 0;
                    }


                    if ($value !== '') {
                        $codeArray[$lowercaseCode] += $value;
                    }
                }
            }


            $this->updateQuestion();


            $existingAssessment = AssessmentModal::where('user_id', $userId)->latest()->first();


            if ($existingAssessment && ($this->offset > 0)) {
                $this->offset += 3;
                $oldResult = $existingAssessment->toArray();


                $resultArray = [];


                foreach ($codeArray as $key => $value) {

                    if ($value !== '') {
                        $resultArray[$key] = (isset($oldResult[$key]) ? $oldResult[$key] : 0) + $value;
                    } else {
                        $resultArray[$key] = isset($oldResult[$key]) ? $oldResult[$key] : 0;
                    }
                }



                if($totalPages == $this->offset / 3){

                       $resultArray['page'] = 0;

                   }else{

                       $resultArray['page'] = $this->offset / 3;

                   }


                $existingAssessment->update($resultArray);
                 $this->assessmentId = $existingAssessment->id;
            } else {
                $this->offset += 3;
                $finalAssessment = array_merge(['user_id' => $userId, 'page' => $this->offset / 3], $codeArray);
                $assessmentId = AssessmentModal::create($finalAssessment);
                $this->assessmentId = $assessmentId->id;
            }


            foreach ($this->answers as $data) {
                $data['user_id'] = Auth::user()->id;
                $data['assessment_id'] = $this->assessmentId;
                AssessmentDetail::createAssessmentDetail($data);
            }

            $this->answers = [];
        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
    }



    public function updateQuestion()
    {
        $this->questions = Question::getQuestion($this->offset, $this->limit);
        if(!$this->questions){
            return redirect()->route('user_detail');
        }
    }

    public function selectAnswer($questionId, $answerId, $answerCodes,$question,$answer)
    {
        $codes = [];
        $codeArr = json_decode(stripslashes($answerCodes), true);
        foreach ($codeArr as $code) {
            $codes[$code['code']] = $code['number'];
        }
        $this->answers[$questionId] = ['question' => $question,'answer' => $answer,'answer_id' => $answerId, 'answer_codes' => $codes];

        $this->skipRender();
    }

    public function render()
    {
        $this->updateQuestion();
        return view('livewire.client.question.assessment', ['questions' => $this->questions]);
    }
}
