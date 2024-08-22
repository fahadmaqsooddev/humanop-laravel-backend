<?php

namespace App\Http\Livewire\Admin\ApproveQuery;

use App\Models\HAIChai\QueryAnswer;
use Livewire\Component;

class ApproveQuery extends Component
{
    public $unApprovedQueries;

    public $listeners = ['rerenderUnApprovedQueries'];

    public function render()
    {

        $this->unApprovedQueries = QueryAnswer::unApprovedQueries();

        return view('livewire.admin.approve-query.approve-query');
    }

    public function approveAnswer($id){

        QueryAnswer::approveAnswer($id);
    }

    public function rerenderUnApprovedQueries(){

        $this->render();
    }
}
