<?php

namespace App\Http\Livewire\Admin\Question;
use App\Models\Admin\Question\Question;
use App\Models\Admin\Answer\Answer;

use Livewire\Component;

class QuestionShow extends Component
{
    public $questions, $questionId, $name, $answers;


    public function editQuestion(int $id)
    {

        $this->questionId = $id;

        $question = Question::singleQuestion($id);


        if (!empty($question))
        {
            $this->name = $question['question'];
        }

    }

    public function updateQuestion()
    {

    }

    public function render()
    {
        $this->questions = Question::allQuestion();

        return view('livewire.admin.question.question-show', [$this->questions]);
    }
}
