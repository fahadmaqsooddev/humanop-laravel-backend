<?php

namespace App\Http\Livewire\Admin\ApproveQuery;

use Livewire\Component;

class ViewQuery extends Component
{

    public $queryId, $question, $answer;

    public function render()
    {
        return view('livewire.admin.approve-query.view-query');
    }
}
