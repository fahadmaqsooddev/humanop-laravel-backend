<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RemoveUserInGroupRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'exists:users,id',
                function ($attribute, $value, $fail) {
                    $exists = DB::table('message_thread_participants')
                        ->where('message_thread_id', request('thread_id'))
                        ->where('user_id', $value)
                        ->where('role', 2)
                        ->exists();

                    if (!$exists) {
                        $fail('The selected user does not exist in this thread or does not have the required role.');
                    }
                },
            ],
        ];
    }


    public function messages()
    {
        return [
            'thread_id.required' => 'The thread ID is required.',
            'thread_id.integer' => 'The thread ID must be an integer.',
            'thread_id.exists' => 'The selected thread does not exist.',
            'user_id.required' => 'Please select a user to add.',
            'user_id.integer' => 'The user ID must be an integer.',
            'user_id.exists' => 'The selected user does not exist.',
            'user_id.unique' => 'This user is already part of the thread.',
        ];
    }

}
