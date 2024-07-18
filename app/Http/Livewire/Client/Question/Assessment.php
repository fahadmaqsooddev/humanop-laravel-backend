<?php

namespace App\Http\Livewire\Client\Question;

use App\Models\Answer;
use App\Models\AnswerCode;
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
    public $energyArr = [];
    public $questions;
    public $multiple = false;
    public $currentPage = 0;
    public $totalQuestion = 0;
    public $assessmentId = 0;

    public function mount()
    {
        // Initialize your component state here
        $this->totalQuestion = Question::totalAssessmentQuestion();
        $this->currentPage = AssessmentModal::getLastPage();
        $this->offset = $this->currentPage * 3;
    }

    public function updateOrder($orderedIds)
    {
        $answer = Answer::where('id', $orderedIds[0]['value'])->first();

        $questionId = $answer->question_id;

        // Find the question in the array by its 'id'
        $questionKey = array_search($questionId, array_column($this->questions, 'id'));
        if ($questionKey !== false && isset($this->questions[$questionKey]['answers'])) {
            // Create a new array for reordered answers
            $newAnswers = [];

            foreach ($orderedIds as $order) {

                // Find the answer within the question's 'answers' array
                $answerKey = array_search($order['value'], array_column($this->questions[$questionKey]['answers'],  'id'));

                if ($answerKey !== false) {
                    // Append the answer to the new answers array
                    $newAnswers[] = $this->questions[$questionKey]['answers'][$answerKey];
                }
            }

            // Assign the new ordered answers array back to the question
            $this->questions[$questionKey]['answers'] = $newAnswers;
        }

        $this->multiple = true;
        // Persist the updated questions
        $this->updatedQuestions($this->questions, $this->answers);
        //calculation
        $i = 3;
        $this->energyArr[$questionId] = [];
        foreach ($orderedIds as $id) {
            if($answer->answer_id){
                $subAnswer = Answer::where('id',$id['value'])->first();
                $answerCode = AnswerCode::where('answer_id', $subAnswer->answer_id)->select(['code', 'number'])->first();
            }else{
                $answerCode = AnswerCode::where('answer_id', $id['value'])->select(['code', 'number'])->first();
            }

            if ($answerCode) {
                $number = (int) $answerCode->number + $i;
                $code = strtolower($answerCode->code);

                if (array_key_exists($code, $this->energyArr[$questionId])) {
                    $this->energyArr[$questionId][$code] += $number;
                } else {
                    $this->energyArr[$questionId][$code] = $number;
                }
                $i--;
            }
        }
        //calculation ends
        $this->emitSelf('questionsUpdated', $this->questions );
    }

    public function updatedQuestions($updatedQuestions,$answers)
    {
        $this->questions = $updatedQuestions;
        $this->answers = $answers;
    }

    protected function mergeEnergyArr()
    {
        $mergedArr = [];

        foreach ($this->energyArr as $questionArr) {
            foreach ($questionArr as $key => $value) {
                if (array_key_exists($key, $mergedArr)) {
                    $mergedArr[$key] += $value;
                } else {
                    $mergedArr[$key] = $value;
                }
            }
        }

        $this->energyArr = $mergedArr;

        return $this->energyArr;
    }

    public function updateAssessment()
    {
        $filteredQuestions = array_filter($this->questions, function($question) {
            return $question['multiple'] == 0;
        });




            if(count($filteredQuestions) != count($this->answers)) {
                $this->emit('scrollToTop');
                $this->skipRender();
                return;
            }

        if(empty($this->energyArr)) {
            $multipleQuestions = array_filter($this->questions, function ($question) {
                return $question['multiple'] == 1;
            });

            //calculation
            foreach ($multipleQuestions as $key => $q) {
                $i = 3;
                $this->energyArr[$q['id']] = [];

                foreach ($q['answers'] as $answer) {

                    $answerCode = AnswerCode::where('answer_id', ($answer['answer_id'] ?? $answer['id'] ))->select(['code', 'number'])->first();

                    if ($answerCode) {
                        $number = (int)$answerCode->number + $i;
                        $code = strtolower($answerCode->code);

                        if (array_key_exists($code, $this->energyArr[$q['id']])) {
                            $this->energyArr[$q['id']][$code] += $number;
                        } else {
                            $this->energyArr[$q['id']][$code] = $number;
                        }
                        $i--;
                    }
                }
            }
        }
        if(!empty($this->energyArr)){
            $energyValues =  $this->mergeEnergyArr();
            $this->energyArr = [];
        }


        //end of calculation

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


            if ($existingAssessment) {

                $this->offset += 3;
                $oldResult = $existingAssessment->toArray();


                $resultArray = [];

                if(!empty($energyValues)){
                    $codeArray =  array_merge($energyValues,$codeArray);
                }

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
            }
//            else {
//                $this->offset += 3;
//                $finalAssessment = array_merge(['user_id' => $userId, 'page' => $this->offset / 3], $codeArray);
//                $assessmentId = AssessmentModal::create($finalAssessment);
//                $this->assessmentId = $assessmentId->id;
//            }


            foreach ($this->answers as $data) {
                $data['user_id'] = Auth::user()->id;
                $data['assessment_id'] = $this->assessmentId;
                AssessmentDetail::createAssessmentDetail($data);
            }

              $this->answers = [];
              $this->multiple = false;
//            dd($this->answers);
        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
    }



    public function updateQuestion()
    {
        $this->questions = Question::getQuestion($this->offset, $this->limit);
        if(!$this->questions){
            return redirect()->route('all_assessment');
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
        if(!$this->multiple){
            $this->updateQuestion();
        }
        return view('livewire.client.question.assessment', ['questions' => $this->questions]);
    }
}
