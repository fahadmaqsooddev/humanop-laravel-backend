<?php

namespace App\Http\Requests\Api\FamilyMatrix;

use Illuminate\Foundation\Http\FormRequest;

class FamilyMatrixNoteRelationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow all authenticated users, or add custom logic
    }

    public function rules(): array
    {
        return [
            'assign_relation_id' => 'required|integer|exists:assign_family_matrix_relationships,id',
        ];
    }

    public function messages(): array
    {
        return [
            'assign_relation_id.required' => 'assign_relation_id is required.',
            'assign_relation_id.integer'  => 'assign_relation_id must be an integer.',
            'assign_relation_id.exists'   => 'assign_relation_id does not exist in relationships.',
        ];
    }
}
