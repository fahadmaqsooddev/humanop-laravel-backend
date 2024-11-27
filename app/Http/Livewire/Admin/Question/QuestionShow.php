<?php

namespace App\Http\Livewire\Admin\Question;

use App\Models\Question;
use Livewire\Component;
use Livewire\WithPagination;

class QuestionShow extends Component
{
    use WithPagination;

    public $search = '';
    public $gender = '';
    protected $questions = 'question';
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    protected $queryString = ['search'];
    protected $listeners = ['refreshQuestion' => 'handleRefreshQuestion'];


    public function handleRefreshQuestion(){
       $this->getQuestion();
    }


    public function getQuestion(){

        $this->questions = Question::allQuestion($this->gender)->paginate($this->perPage);

    }

    public function render()
    {
        $this->getQuestion();
        return view('livewire.admin.question.question-show', [
            'questions' => $this->questions,
        ]);
    }
}
