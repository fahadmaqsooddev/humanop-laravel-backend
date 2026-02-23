<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionFromAppRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'purchase_id'   => 'required',
            'purchase_name' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'purchase_id.required'   => 'Purchase ID is required.',
            'purchase_name.required' => 'Purchase name is required.',
        ];
    }
}
