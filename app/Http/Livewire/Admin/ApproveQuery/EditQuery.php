<?php

namespace App\Http\Livewire\Admin\ApproveQuery;

use App\Models\HAIChai\QueryAnswer;
use Livewire\Component;

class EditQuery extends Component
{
    public $queryId, $question, $answer;

    public function render()
    {
        return view('livewire.admin.approve-query.edit-query');
    }

    public function updateAndApproveAnswer(){

        $data['answer'] = $this->answer;

        QueryAnswer::updateQueryAnswer($data, $this->queryId);

        toastr()->success('Answer updated');

        $this->emit('closeEditQueryModal', ['id' => $this->queryId]);

        $this->emit('rerenderUnApprovedQueries');
    }
}
