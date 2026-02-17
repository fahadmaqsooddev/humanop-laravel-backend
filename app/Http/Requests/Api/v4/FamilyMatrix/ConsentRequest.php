<?php

namespace App\Http\Requests\Api\v4\FamilyMatrix;

use Illuminate\Foundation\Http\FormRequest;

class ConsentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'target_id' => 'required|integer|exists:users,id',
            'consent' => 'required|integer|in:1,2',
        ];
    }

    public function messages(): array
    {
        return [
            'target_id.required' => 'The target ID is required.',
            'target_id.exists' => 'The target user does not exist.',
            'consent.required' => 'You must provide a consent value.',
            'consent.integer' => 'Consent must be a valid integer.',
            'consent.in' => 'Consent must be 1 (approved) or 2 (declined).',
        ];
    }
}
