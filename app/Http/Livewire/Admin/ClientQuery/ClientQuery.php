<?php

namespace App\Http\Livewire\Admin\ClientQuery;

use Livewire\Component;
use App\Models\HAIChai\ClientQuery as Query;
use Livewire\WithPagination;

class ClientQuery extends Component
{

    use WithPagination;

    protected $queries;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshQuery' => 'handleRefreshQuery'];

    public function handleRefreshQuery()
    {

        $this->render();
    }

    public function render()
    {
        $this->queries = Query::getQueries();

        return view('livewire.admin.client-query.client-query', ['queries' => $this->queries]);

    }

}
