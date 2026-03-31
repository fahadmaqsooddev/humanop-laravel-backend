<?php

namespace App\Http\Requests\v4\Api\Client\Optimizationtrend;

use Illuminate\Foundation\Http\FormRequest;

class GetOptimizationTrendAnalysisRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'current_assessment_id' => 'required|integer|exists:assessments,id',
            'previous_assessment_id' => 'required|integer|exists:assessments,id',
        ];
    }

    public function messages()
    {
        return [
            'current_assessment_id.required' => 'Current assessment ID is required.',
            'current_assessment_id.integer' => 'Current assessment ID must be a valid number.',
            'current_assessment_id.exists' => 'The selected current assessment does not exist.',

            'previous_assessment_id.required' => 'Previous assessment ID is required.',
            'previous_assessment_id.integer' => 'Previous assessment ID must be a valid number.',
            'previous_assessment_id.exists' => 'The selected previous assessment does not exist.',
        ];
    }
}
