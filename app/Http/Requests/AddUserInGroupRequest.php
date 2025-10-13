<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddUserInGroupRequest extends FormRequest
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
            'user_ids' => ['required', 'array', 'min:1',],
            'user_ids.*' => ['integer', 'exists:users,id',
                Rule::unique('message_thread_participants', 'user_id')->where(function ($query) {
                    return $query->where('message_thread_id', request('thread_id'));
                }),
            ],
        ];
    }

    public function messages()
    {
        return [
            'thread_id.required' => 'The thread ID is required.',
            'thread_id.integer' => 'The thread ID must be an integer.',
            'thread_id.exists' => 'The selected thread does not exist.',
            'user_ids.required' => 'Please select at least one user.',
            'user_ids.array' => 'The users field must be an array.',
            'user_ids.min' => 'You must add at least one user.',
            'user_ids.*.integer' => 'Each user ID must be a valid integer.',
            'user_ids.*.exists' => 'One or more selected users do not exist.',
            'user_ids.*.unique' => 'A selected user is already part of this thread.',
        ];
    }


}
