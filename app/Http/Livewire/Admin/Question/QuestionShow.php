<?php

namespace App\Http\Livewire\Admin\Question;

use Livewire\Component;

class QuestionShow extends Component
{

    public $questions;

    public function mount($questions)
    {

        $this->questions = $questions->toArray();

    }

    public function render()
    {
        return view('livewire.admin.question.question-show');
    }
}
