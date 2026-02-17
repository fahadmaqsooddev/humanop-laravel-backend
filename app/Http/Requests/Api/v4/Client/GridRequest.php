<?php

namespace App\Http\Requests\Api\v4\Client;

use App\Helpers\Helpers;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GridRequest extends FormRequest
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
            'assessment_id' => [
                'required',
                Rule::exists('assessments', 'id')->where('user_id', Helpers::getUser()->id),
            ],
        ];
    }

    public function messages()
    {
        return [
            'assessment_id.required' => 'Assessment ID is required.',
            'assessment_id.exists' => 'Assessment ID does not exist or does not belong to you.',
        ];
    }
}
