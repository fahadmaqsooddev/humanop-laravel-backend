<?php

namespace App\Http\Requests\Api\Client\Auth;

use Illuminate\Foundation\Http\FormRequest;

class TwoFactorAuthRequest extends FormRequest
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
            'two_factor_auth' => 'required|in:1,2',
        ];
    }

    public function messages()
    {
        return [
            'two_factor_auth.required' => 'Two factor auth is required.',
            'two_factor_auth.in' => 'Two factor auth value must be either 1 or 2.',
        ];
    }
}
