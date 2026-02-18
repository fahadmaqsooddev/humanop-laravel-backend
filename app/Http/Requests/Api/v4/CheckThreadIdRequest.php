<?php

namespace App\Http\Requests\Api\v4;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckThreadIdRequest extends FormRequest
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
            'thread_id' => ['required', 'integer', "exists:message_threads,id",],
        ];
    }

    public function messages()
    {
        return [
            'thread_id.required' => 'The thread ID is required.',
            'thread_id.integer' => 'The thread ID must be an integer.',
            'thread_id.unique' => 'A thread with this ID already exists.',
        ];
    }


}
