<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcceptOrRejectGroupRequest extends FormRequest
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
            'thread_id' => 'required|integer|exists:message_threads,id',
            'member_id' => 'required|integer|exists:users,id',
            'accept_or_reject' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'thread_id.required' => 'Thread ID is required.',
            'thread_id.integer' => 'Thread ID must be an integer.',
            'thread_id.exists' => 'The selected thread does not exist.',

            'member_id.required' => 'Member ID is required.',
            'member_id.integer' => 'Member ID must be an integer.',
            'member_id.exists' => 'The selected member does not exist.',
        ];
    }
}
