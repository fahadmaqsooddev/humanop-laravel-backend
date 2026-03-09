<?php

namespace App\Http\Livewire\Admin\ImpactProjects;
use Livewire\Component;
use App\Models\ImpactProject; // Make sure you have this model
class AddImpactProject extends Component{


    public $title;
    public $description;
    public $hp_required;
    public $verification_text;
    public $status = 1;


    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'hp_required' => 'required|integer|min:1',
        'verification_text' => 'nullable|string',
        'status' => 'required|boolean',
    ];

     public function createProject()
    {
        $this->validate();

        $cleanVerificationText = strip_tags($this->verification_text);
       ImpactProject::createProject([
            'title' => $this->title,
            'description' => $this->description,
            'hp_required' => $this->hp_required,
            'verification_text' => $cleanVerificationText, // save plain text
            'status' => $this->status,
        ]);

        // Reset form fields
        $this->reset(['title', 'description', 'hp_required', 'verification_text', 'status']);
        $this->emitUp('impactProjectAdded');

        $this->dispatchBrowserEvent('closeModal');

        session()->flash('message', 'Impact project created successfully.');
    }


    public function render()
    {

        return view('livewire.admin.impact-projects.add-impact-project');

    }

}