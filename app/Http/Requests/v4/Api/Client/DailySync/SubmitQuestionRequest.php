<?php

namespace App\Http\Requests\v4\Api\Client\DailySync;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
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
            'question_id' => ['required', 'integer','exists:daily_sync_questions,id'],
            'response'   => ['required', 'string', 'max:500'],
        ];
    }


     /**
     * Configure the validator instance.
     * Ensures question_id belongs to the given session_id.
     *
     * @param \Illuminate\Validation\Validator $validator
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $sessionId  = $this->input('session_id');
            $questionId = $this->input('question_id');

            $exists = DB::table('daily_sync_responses')
                ->where('session_id', $sessionId)
                ->where('question_id', $questionId)
                ->exists();

            if (!$exists) {
                $validator->errors()->add(
                    'question_id',
                    'The selected question does not belong to the given session.'
                );
            }
        });
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
            'response.max' => 'Response may not exceed 500 characters.',
        ];
    }
}
