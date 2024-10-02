<?php

namespace App\Http\Livewire\Admin\ApproveQuery;

use App\Models\Assessment;
use App\Models\HAIChai\ClientQuery;
use App\Models\HAIChai\QueryAnswer;
use Livewire\Component;

class EditQuery extends Component
{
    public $queryId, $question, $answer, $updatedAnswer ,$mainQueryId;

    public function render()

    {

        $query = ClientQuery::singleQuery($this->mainQueryId);

        $grid = Assessment::getLatestAssessment($query['user_id']);

        return view('livewire.admin.approve-query.edit-query',compact('grid'));
    }

    public function updateAndApproveAnswer(){

        $data['answer'] = $this->updatedAnswer;

        QueryAnswer::updateQueryAnswer($data, $this->queryId);

        toastr()->success('Answer updated');

        $this->emit('closeEditQueryModal', ['id' => $this->queryId]);

        $this->emit('rerenderUnApprovedQueries');
    }
}
