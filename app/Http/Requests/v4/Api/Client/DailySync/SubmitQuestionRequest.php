<?php

namespace App\Http\Requests\v4\Api\Client\DailySync;

use Illuminate\Foundation\Http\FormRequest;

class SubmitQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'session_id' => ['required', 'integer', 'exists:daily_sync_sessions,id'],
            'question_id' => ['required', 'integer', 'exists:daily_sync_questions,id'],
            'response' => ['required', 'string', 'max:65535'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'session_id.required' => 'Session ID is required.',
            'session_id.integer' => 'Session ID must be an integer.',
            'session_id.exists' => 'The selected session does not exist.',
            'question_id.required' => 'Question ID is required.',
            'question_id.integer' => 'Question ID must be an integer.',
            'question_id.exists' => 'The selected question does not exist.',
            'response.required' => 'Response is required.',
            'response.string' => 'Response must be a string.',
            'response.max' => 'Response may not exceed 65535 characters.',
        ];
    }
}
