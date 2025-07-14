<?php

namespace App\Http\Requests\Api\Client;

use Illuminate\Foundation\Http\FormRequest;

class CompleteWatchVideoRequest extends FormRequest
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
            'assessment_id' => 'required|exists:assessments,id',
            'video_name' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'assessment_id.required' => 'The assessment ID is required.',
            'assessment_id.exists' => 'The selected assessment does not exist.',

            'video_name.required' => 'The video name is required.',
            'video_name.string' => 'The video name must be a valid string.',
            'video_name.max' => 'The video name must not exceed 255 characters.',
        ];
    }

}
