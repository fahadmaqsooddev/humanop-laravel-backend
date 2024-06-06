<?php

namespace App\Http\Livewire\Admin\Question;

use Livewire\Component;
use App\Models\Admin\Question\Question;
use App\Models\Admin\Answer\Answer;

class QuestionUpdateForm extends Component
{

    public $question, $answers;
    public function mount($question, $answers)
    {
        $this->question = $question;
        $this->answers = $answers;
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

    public function render()
    {
        return view('livewire.admin.question.question-update-form');
    }
}
