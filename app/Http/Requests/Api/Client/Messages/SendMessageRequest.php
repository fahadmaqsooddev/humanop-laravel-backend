<?php

namespace App\Http\Requests\Api\Client\Messages;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
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
            'thread_id' => ['required', 'integer', 'exists:message_threads,id',],
            'message' => ['required', 'string', 'max:1000',],
            'upload_file' => 'nullable|file|mimes:jpeg,jpg,png,gif|max:204800',
        ];
    }

    public function messages()
    {
        return [
            'thread_id.required' => 'The thread ID is required.',
            'thread_id.integer' => 'The thread ID must be an integer.',
            'thread_id.exists' => 'The selected thread does not exist.',
            'message.required' => 'Please enter a message before sending.',
            'message.string' => 'The message must be a valid text string.',
            'message.max' => 'The message may not be greater than 1000 characters.',
            'upload_file.file' => 'The upload file must be a file.',
            'upload_file.mimes' => 'The upload file must be a file.',
            'upload_file.max' => 'The upload file is too large.',
        ];
    }

}
