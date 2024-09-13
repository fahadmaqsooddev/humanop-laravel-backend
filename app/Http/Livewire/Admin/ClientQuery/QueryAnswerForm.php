<?php

namespace App\Http\Livewire\Admin\ClientQuery;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\HAIChai\ClientQuery;
use App\Models\HAIChai\QueryAnswer;
use App\Helpers\Helpers;
use App\Models\User;
use App\Models\Assessment;
use App\Models\Admin\Code\CodeDetail;

class QueryAnswerForm extends Component
{

    public $queryId;
    public $answer;

    public function submitForm()
    {
        DB::beginTransaction();

        try {

            $queryData = ClientQuery::updateQuery($this->queryId);

            QueryAnswer::createAnswer($queryData['id'], $this->answer);

            DB::commit();

            session()->flash('success', "Answer submit successfully");

            $this->emitUp('refreshQuery');

            $this->emit('closeAnswerModal', ['id' =>$this->queryId]);


        }catch (\Exception $exception)
        {
            DB::rollBack();

            session()->flash('error', $exception->getMessage());
        }
    }

    public function render()
    {
        $query = ClientQuery::singleQuery($this->queryId);
        $grid = Assessment::getLatestAssessment($query['user_id']);

        return view('livewire.admin.client-query.query-answer-form', compact('query', 'grid'));
    }
}
