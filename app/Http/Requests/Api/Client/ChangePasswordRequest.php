<?php

namespace App\Http\Requests\Api\Client;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'current_password' => 'required',
            'new_password' => [
                'required',
                'confirmed',
                'min:6',
                'regex:/[!@#$%^&*(),.?":{}|<>]/', // At least one special character
                'regex:/[0-9].*[0-9]/',           // At least two numbers
            ],
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => 'The current password is required.',
            'new_password.required' => 'The new password is required.',
            'new_password.min' => 'The new password should be at least 6 characters long.',
            'new_password.regex' => 'The new password should contain at least one special character and two numbers.',
            'new_password.different' => 'The new password must be different from the current password.',
        ];
    }
}
