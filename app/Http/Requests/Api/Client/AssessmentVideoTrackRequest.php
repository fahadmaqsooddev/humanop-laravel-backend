<?php

namespace App\Http\Requests\Api\Client;

use Illuminate\Foundation\Http\FormRequest;

class AssessmentVideoTrackRequest extends FormRequest
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
            'assessment_id' => 'required|integer',
            'user_id' => 'required|integer',
            'video_time' => ['required', 'regex:/^\d{2}:\d{2}$/'],
            'video_name' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'assessment_id.required' => 'Assessment id is required.',
            'assessment_id.integer' => 'Assessment id must be integer.',
            'user_id.integer' => 'User id must be integer.',
            'user_id.required' => 'User id is required.',
            'video_time.required' => 'Video time is required.',
            'video_time.numeric' => 'Video time must be a valid number (e.g. 12.50).',
            'video_name.required' => 'Video name is required.',
        ];
    }
}
