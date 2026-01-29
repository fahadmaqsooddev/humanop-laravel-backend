<?php

namespace App\Http\Livewire\Admin\Question;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Question;
use App\Models\Admin\Answer\Answer;

class QuestionUpdateForm extends Component
{

    public $question, $answers, $sub_question, $sub_answer = [];
    public $subQuestions;


    protected function rules()
    {
        return [
            'sub_question' => 'required|string',
            'sub_answer'   => 'required|array',
            'sub_answer.*' => 'required|string',
        ];
    }

    protected $messages = [
        'sub_question.required' => 'Sub question is required.',
        'sub_answer.required'  => 'All answer fields are required.',
        'sub_answer.*.required'=> 'Answer field is required.',
    ];

    public function mount($question, $answers)
    {
        $this->question = $question;
        $this->answers = $answers;
        foreach ($answers as $index => $answer) {
            $this->sub_answer[$index] = '';
        }
    }


    public function updateSubQuestion($subQuestionId)
    {
        try {
        DB::transaction(function() use ($subQuestionId) {
            $filteredSubQuestions = array_filter($this->subQuestions, function ($subQuestion) use ($subQuestionId) {
                return $subQuestion['id'] === $subQuestionId;
            });
            $reindexedSubQuestions = array_values($filteredSubQuestions);

            $data = array_shift($reindexedSubQuestions);
             if($data['question'] == ''){
                 session()->flash('error'.$subQuestionId, 'All Fields Are Required');
                 return;
             }
            Question::updateQuestion(['question' => $data['question']],$subQuestionId);

            foreach ($data['answers'] as $answer){
                if($answer['answer'] == ''){
                    session()->flash('error'.$subQuestionId, 'All Fields Are Required');
                    DB::rollBack();
                    return;
                }
                Answer::updateAnswer(['answer' => $answer['answer']],$answer['id']);
            }

            session()->flash('success'.$subQuestionId, 'Subquestion updated successfully.');
        });
        }catch(\Exception $exception){
            session()->flash('error'.$subQuestionId, $exception->getMessage());
        }
    }


    public function updateQuestion()
    {

        try {
            $question = $this->only(['question']);
            $answer = $this->only(['answers']);

            Question::updateQuestion($question['question'], $question['question']['id']);

            Answer::updateAnswer($answer['answers']);
            $this->emit('refreshQuestion');
            session()->flash('success', 'Question updated successfully.');

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }
    }

    public function createSubQuestion()
    {

        // Validate
        $this->validate();

        try {
            // Save
            $new_question = Question::createQuestion($this->question, $this->sub_question);
            Answer::createAnswer($this->question['answers'], $this->sub_answer, $new_question['id']);

            // Reset for next submission (keys maintained)
            $this->sub_question = '';
            $this->sub_answer = array_fill(0, count($this->sub_answer), '');

            $this->emit('refreshQuestion');

            session()->flash('success', 'Sub Question created successfully.');
        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
    }


    public function render()
    {
        return view('livewire.admin.question.question-update-form');
    }
}
