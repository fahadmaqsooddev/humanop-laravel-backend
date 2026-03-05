<?php

namespace App\Http\Requests\v4\Api\Client;
use Illuminate\Foundation\Http\FormRequest;

class ImpactContributionRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'project_id' => 'required|exists:impact_projects,id',
        ];
    }

    /**
     * Custom messages for validation (optional)
     */
    public function messages()
    {
        return [
            'project_id.required' => 'Project ID is required.',
            'project_id.exists' => 'Selected project does not exist.',
        ];
    }
}
