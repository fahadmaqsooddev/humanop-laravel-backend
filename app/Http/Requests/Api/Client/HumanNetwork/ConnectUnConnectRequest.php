<?php

namespace App\Http\Requests\Api\Client\HumanNetwork;

use Illuminate\Foundation\Http\FormRequest;

class ConnectUnConnectRequest extends FormRequest
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
            'friend_id' => 'required|exists:users,id,deleted_at,NULL',
            'type' => 'required|in:connect,un-connect,accept',
        ];
    }

    public function messages()
    {
        return [
            'friend_id.required' => 'Friend id is required',
            'friend_id.exists' => 'Friend id does not exists',
            'type.required' => 'Type is required',
            'type.in' => 'Invalid type it must be (connect, un-connect, accept)',
        ];
    }
}
