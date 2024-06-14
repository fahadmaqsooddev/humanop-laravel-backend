<?php

namespace App\Http\Livewire\Admin\Question;

use Livewire\Component;
use App\Models\Question;
use App\Models\Admin\Answer\Answer;

class QuestionUpdateForm extends Component
{

    public $question, $answers, $sub_question, $sub_answer = [];

    public function mount($question, $answers)
    {
        $this->question = $question;
        $this->answers = $answers;

        foreach ($answers as $index => $answer) {
            $this->sub_answer[$index] = '';
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

        try {

            $new_question = Question::createQuestion($this->question, $this->sub_question);

            Answer::createAnswer($this->question['answers'], $this->sub_answer, $new_question['id']);

            $this->sub_question = '';
            $this->sub_answer = '';
            $this->emit('refreshQuestion');

            session()->flash('success', 'Sub Question create successfully.');

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }

    }

    public function render()
    {
        return view('livewire.admin.question.question-update-form');
    }
}
