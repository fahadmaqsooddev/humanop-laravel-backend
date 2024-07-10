<?php

namespace App\Http\Requests\Api\Client;

use App\Helpers\Helpers;
use Illuminate\Foundation\Http\FormRequest;

class AssessmentAnswersRequest extends FormRequest
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
            'assessment_id' => 'required|exists:assessments,id,user_id,' . Helpers::getUser()->id
        ];
    }

    public function messages()
    {
        return [
            'assessment_id.required' => 'Assessment id is required',
            'assessment_id.exists' => 'Assessment id does not exists',
        ];
    }
}
