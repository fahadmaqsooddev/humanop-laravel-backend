<?php

namespace App\Http\Requests\Api\Client;

use Illuminate\Foundation\Http\FormRequest;

class AssessmentSubmitRequest extends FormRequest
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
            'answer_ids' => 'required|array',
            'answer_ids.0' => 'required',
            'answer_ids.1' => 'nullable',
            'answer_ids.2' => 'nullable'
        ];
    }

    public function messages()
    {
        return [
            'answer_ids.required' => 'Answer ids are required',
            'answer_ids.array' => 'Answer ids must be an array',
            'answer_ids.0.required' => '1st answer is required',
            'answer_ids.1.required' => '2nd answer is required',
            'answer_ids.2.required' => '3rd answer is required',
        ];
    }
}
