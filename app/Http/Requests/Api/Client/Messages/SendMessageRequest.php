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
            'receiver_id' => 'required|exists:users,id,deleted_at,NULL',
            'message' => 'required|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'message_thread_id.required' => 'Message thread id is required',
            'message_thread_id.exists' => 'Message thread id does not exists',
            'message.required' => 'Message is required',
            'message.max' => 'Message character exceeded',
        ];
    }
}
