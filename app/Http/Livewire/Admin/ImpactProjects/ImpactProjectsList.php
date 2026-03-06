<?php

namespace App\Http\Livewire\Admin\ImpactProjects;
use Livewire\Component;
use App\Models\ImpactProject;
class ImpactProjectsList extends Component{

    public $title;
    public $description;
    public $hp_required;
    public $verification_text;
    public $status = 1;
    public $edit_id;

    protected $listeners = [
        'impactProjectAdded' => 'refreshList',
        'editProject' => 'loadProject',
        'confirmDeleteProject' => 'deleteProjectConfirm',
        'deleteProject' => 'deleteProject'
    ];

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'hp_required' => 'required|integer|min:1',
        'verification_text' => 'nullable|string',
        'status' => 'required|boolean',
    ];

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

    public function loadProject($id)
    {

        $project = ImpactProject::findOrFailById($id);
        $this->edit_id = $project->id;
        $this->title = $project->title;
        $this->description = $project->description;
        $this->hp_required = $project->hp_required;
        $this->verification_text = $project->verification_text;
        $this->status = $project->status;
        $this->dispatchBrowserEvent('show-edit-modal');
    }

    public function updateProject()
    {
        $validatedData = $this->validate();

        $project = ImpactProject::findOrFailById($this->edit_id);
        $project->updateProject($validatedData);

        session()->flash('success', 'Impact Project Updated Successfully!');
        $this->refreshList();
        $this->dispatchBrowserEvent('hide-edit-modal');
        $this->reset(['edit_id', 'title', 'description', 'hp_required', 'verification_text', 'status']);
    }
    public function deleteProjectConfirm($id)
    {
        
        $this->dispatchBrowserEvent('show-delete-confirmation', [
            'project_id' => $id,
            'message' => 'Are you sure you want to delete this Impact Project? This action cannot be undone.',
        ]);
    }

    public function deleteProject($id)
    {
        $project = ImpactProject::findOrFailById($id);
        $project->deleteProject();

        $this->refreshList();
        session()->flash('success', 'Impact Project Deleted Successfully!');
    }

}