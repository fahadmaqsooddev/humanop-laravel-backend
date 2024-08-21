<?php

namespace App\Http\Livewire\Admin\ClientQuery;

use App\Models\HAIChai\QueryAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Models\HAIChai\ClientQuery as Query;

class ClientQuery extends Component
{

    public $queries;
    protected $listeners = ['refreshQuery' => 'handleRefreshQuery'];

    public function handleRefreshQuery(){

        $this->render();
    }

    public function render()
    {
        $this->queries = Query::getQueries();

        return view('livewire.admin.client-query.client-query');
    }
}
