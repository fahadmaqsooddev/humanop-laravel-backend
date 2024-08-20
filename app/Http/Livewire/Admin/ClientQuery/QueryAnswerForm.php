<?php

namespace App\Http\Livewire\Admin\ClientQuery;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\HAIChai\ClientQuery;
use App\Models\HAIChai\QueryAnswer;

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

//            $this->emit('modalHide', $this->queryId);
            $this->emit('refreshQuery');

        }catch (\Exception $exception)
        {
            DB::rollBack();

            session()->flash('error', $exception->getMessage());
        }
    }

    public function render()
    {
        $query = ClientQuery::singleQuery($this->queryId);

        return view('livewire.admin.client-query.query-answer-form', compact('query'));
    }
}
