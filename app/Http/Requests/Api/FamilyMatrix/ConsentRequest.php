<?php

namespace App\Http\Requests\Api\FamilyMatrix;

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
            'consent'   => 'required|in:yes,no',
        ];
    }

    public function messages(): array
    {
        return [
            'target_id.required' => 'The target ID is required.',
            'target_id.exists'   => 'The target user does not exist.',
            'consent.required'   => 'You must indicate consent (yes or no).',
            'consent.in'         => 'Consent must be either yes or no.',
        ];
    }
}
