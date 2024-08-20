<?php

namespace App\Http\Livewire\Admin\ClientQuery;

use Livewire\Component;
use App\Models\HAIChai\ClientQuery as Query;

class ClientQuery extends Component
{

    public $queries;
    protected $listeners = ['refreshQuery' => 'handleRefreshQuery'];

    public function handleRefreshQuery(){
        $this->getQueries();

    }

    public function getQueries(){
        $this->queries = Query::getQueries();
    }

    public function render()
    {
        $this->getQueries();
        return view('livewire.admin.client-query.client-query', [
        'queries' => $this->queries,
        ]);
    }
}
