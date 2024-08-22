<?php

namespace App\Http\Requests\Api\Client\User;

use Illuminate\Foundation\Http\FormRequest;

class GoogleLoginSignupRequest extends FormRequest
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
            'google_access_token' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'google_access_token.required' => 'Google access token is required',
        ];
    }
}
