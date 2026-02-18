<?php

namespace App\Http\Requests\Api\v4;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class RegisterFirstStepRequest extends FormRequest
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
        $required = (request()->input('google_id') || request()->input('apple_id')) ? 'nullable' : 'required';

        return [
            'password' => $required . '|string|min:6',
            'email' => [
                $required,
                'unique:users,email,NULL,id,deleted_at,NULL',
                'regex:/^[a-zA-Z0-9]+([._-]?[a-zA-Z0-9]+)*@[a-zA-Z0-9-]+\.[a-zA-Z]{2,}$/' //Regex Validation
            ],
        ];
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $email = $this->input('email');

            if ($email) {
                // Starts with letter or number
                if (!preg_match('/^[a-zA-Z0-9]/', $email)) {
                    $validator->errors()->add('email', 'Email must start with a letter or number.');
                }

                // Cannot start or end with special character
                if (preg_match('/^[._-]|[._-]@|[._-]$/', $email)) {
                    $validator->errors()->add('email', 'Email cannot start or end with special characters (., _, -).');
                }

                // No consecutive dots
                if (preg_match('/\.\./', $email)) {
                    $validator->errors()->add('email', 'Email cannot contain consecutive dots (..).');
                }

                // Domain TLD at least 2 letters
                if (!preg_match('/\.[a-zA-Z]{2,}$/', $email)) {
                    $validator->errors()->add('email', 'Email domain must end with at least 2 letters.');
                }

                // Allowed characters only
                if (preg_match('/[^a-zA-Z0-9._@-]/', $email)) {
                    $validator->errors()->add('email', 'Email can contain only letters, numbers, dots (.), underscores (_) and hyphens (-).');
                }
            }
        });
    }

    public function messages()
    {
        return [
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a string.',
            'password.min' => 'Password must be at least 6 characters.',

            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
        ];
    }
}