<?php

namespace App\Http\Requests\Api\Client;

use Illuminate\Foundation\Http\FormRequest;

class AddRecentPlayerRequest extends FormRequest
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
            'user_id'  => 'required|exists:users,id',
            'audio_id' => 'required|exists:uploads,id',
            'time'     => 'required', // optional format check if time is like 00:03:45
        ];
    }

    public function messages()
    {
        return [
            'user_id.required'  => 'User ID is required.',
            'user_id.exists'    => 'The selected user does not exist.',
            'audio_id.required' => 'Audio ID is required.',
            'audio_id.exists'   => 'The selected audio does not exist.',
            'time.required'     => 'Playback time is required.',
        ];
    }

}
