<?php

namespace App\Http\Requests\Api\FamilyMatrix;

use Illuminate\Foundation\Http\FormRequest;

class FamilyMatrixNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'assign_relation_id' => 'required|integer|exists:assign_family_matrix_relationships,id',
            'note'             => 'required|string|max:1000',
        ];
    }
}
