<?php

namespace App\Http\Livewire\Admin\ApproveQuery;

use App\Models\HAIChai\QueryAnswer;
use Livewire\Component;
use Livewire\WithPagination;

class ApproveQuery extends Component
{
    public $perPage = 10, $page = 1;

    public $listeners = ['rerenderUnApprovedQueries'];

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {

        $unApprovedQueries = QueryAnswer::unApprovedQueries($this->perPage);

        return view('livewire.admin.approve-query.approve-query', compact('unApprovedQueries'));
    }

    public function approveAnswer($id)
    {

        QueryAnswer::approveAnswer($id);
    }

    public function rerenderUnApprovedQueries()
    {

        $this->render();
    }
}
