<?php

namespace App\Http\Requests\Api\Client;

use Illuminate\Foundation\Http\FormRequest;

class ShareDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'candidate_id' => 'required',
            'company_name' => 'required|array',
        ];
    }

    public function messages()
    {
        return [
            'candidate_id.required' => 'Candidate ID is required.',
            'company_name.required' => 'Company Name is required.',
        ];
    }
}
