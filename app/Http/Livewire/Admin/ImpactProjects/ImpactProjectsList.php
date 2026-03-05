<?php

namespace App\Http\Livewire\Admin\ImpactProjects;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\ImpactProject;
class ImpactProjectsList extends Component{


   protected $listeners = ['impactProjectAdded' => 'refreshList'];
    public $impact_projects = [];

    public function mount()
    {
        $this->impact_projects = ImpactProject::fetchAll();
    }

    public function refreshList()
    {
        $this->impact_projects = ImpactProject::fetchAll();
    }

    public function render()
    {
        return view('livewire.admin.impact-projects.impact-projects-list');
    }

}