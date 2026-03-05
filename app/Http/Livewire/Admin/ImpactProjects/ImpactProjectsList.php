<?php

namespace App\Http\Livewire\Admin\ImpactProjects;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\ImpactProject;
class ImpactProjectsList extends Component{

    public $impact_projects=[];
    public function render()
    {

        $this->impact_projects=ImpactProject::fetchAll();
        return view('livewire.admin.impact-projects.impact-projects-list');

    }

}