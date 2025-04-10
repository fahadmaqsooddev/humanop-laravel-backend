<?php

namespace App\Http\Requests\Api\Client;

use App\Helpers\Helpers;
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
        $user=Helpers::getUser();

        $required= (!empty($user->google_id)|| !empty($user->apple_id)) ? 'nullable':'required';

        return [
            'current_password' => $required,
            'new_password' => [
                'required',
                'confirmed',
                'min:6',
                'max:22'
            ],
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => 'The current password is required.',
            'new_password.required' => 'The new password is required.',
            'new_password.min' => 'The new password should be at least 6 characters long.',
            'new_password.max' => 'The new password should be at less then or equal to 22 characters long.',
            'new_password.different' => 'The new password must be different from the current password.',
        ];
    }
}
