<?php

namespace App\Http\Requests\Api\v4;

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
        $userRequiredRule = request()->filled('member_id') ? 'nullable' : 'required';
        $memberRequiredRule = request()->filled('user_id') ? 'nullable' : 'required';

        return [
            'thread_id' => ['required', 'integer', 'exists:message_threads,id',],

            'member_id' => [$memberRequiredRule, 'integer', 'exists:users,id',
                function ($attribute, $value, $fail) {
                    if (!$value || !request('thread_id')) {
                        return;
                    }

                    $exists = DB::table('message_thread_participants')
                        ->where('message_thread_id', request('thread_id'))
                        ->where('user_id', $value)
                        ->whereIn('role', [1,2])
                        ->exists();

                    if (!$exists) {
                        $fail('The selected member does not exist in this thread or does not have the required role.');
                    }
                },
            ],

            'user_id' => [$userRequiredRule, 'integer', 'exists:users,id',],
        ];
    }


    public function messages()
    {
        return [
            'thread_id.required' => 'Thread ID is required.',
            'thread_id.integer' => 'Thread ID must be an integer.',
            'thread_id.exists' => 'The selected thread does not exist.',

            'member_id.required' => 'Member ID is required when User ID is not provided.',
            'member_id.integer' => 'Member ID must be an integer.',
            'member_id.exists' => 'The selected member does not exist.',

            'user_id.required' => 'User ID is required when Member ID is not provided.',
            'user_id.integer' => 'User ID must be an integer.',
            'user_id.exists' => 'The selected user does not exist.',
        ];
    }


}
