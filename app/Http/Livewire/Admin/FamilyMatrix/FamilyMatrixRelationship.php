<?php

namespace App\Http\Livewire\Admin\FamilyMatrix;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FamilyMatrixRelationship extends Component
{

    public $relationship_name, $relationshipId, $relationshipName;

    protected $listeners = ['deleteRelationship'];

    protected $rules = [
        'relationship_name' => 'required|string|max:255|unique:family_matrix_relationships,relationship_name',
    ];

    protected $messages = [
        'relationship_name.required' => 'The name field is required.',
        'relationship_name.string' => 'The name must be a valid string.',
        'relationship_name.max' => 'The name must not exceed 255 characters.',
        'relationship_name.unique' => 'This relationship name already exists.',
    ];

    public function getRelationships()
    {
        try {

            return \App\Models\FamilyMatrix\FamilyMatrixRelationship::getRelationships();

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());
        }

    }

    public function submitForm()
    {

        try {

            $validatedData = $this->validate();

            \App\Models\FamilyMatrix\FamilyMatrixRelationship::createRelationship($validatedData);

            $this->resetForm();

            session()->flash('success', 'Family Matrix Relationship create successfully.');

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }

    }

    public function editRelationshipModal($id, $name)
    {
        $this->relationshipId = $id;
        $this->relationshipName = $name;

    }

    public function updateForm()
    {

        try {

            \App\Models\FamilyMatrix\FamilyMatrixRelationship::updateRelationship($this->relationshipId, $this->relationshipName);

            session()->flash('success', ' relationship Updated successfully.');

            $this->render();

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }

    }

    public function deleteRelationship($id)
    {

        \App\Models\FamilyMatrix\FamilyMatrixRelationship::deleteRelationship($id);

        $this->resetForm();

        session()->flash('success', 'Relationship Deleted Successfully.');

    }

    public function resetForm()
    {

        $this->relationship_name = '';

    }

    public function render()
    {

        $relationships = $this->getRelationships();

        return view('livewire.admin.family-matrix.family-matrix-relationship', ['relationships' => $relationships]);

    }

}
