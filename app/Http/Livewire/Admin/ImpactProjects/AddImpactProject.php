<?php

namespace App\Http\Livewire\Admin\ImpactProjects;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

     public function createCode()
    {
        $this->validate();

       ImpactProject::createProject([
            'title' => $this->title,
            'description' => $this->description,
            'hp_required' => $this->hp_required,
            'verification_text' => $this->verification_text,
            'status' => $this->status,
            'text' => $this->text,
        ]);

        // Reset form fields
        $this->reset(['title', 'description', 'hp_required', 'verification_text', 'status']);

        $this->emit('impactProjectAdded');

        session()->flash('message', 'Impact project created successfully.');
    }


    public function render()
    {

        return view('livewire.admin.impact-projects.add-impact-project');

    }

}