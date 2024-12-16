<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        $required = (request()->input('google_id', null) || request()->input('apple_id', null)) ? 'nullable' : 'required';

        return [
//            'full_name' => 'required|string|max:255',
            'email' => $required . '|email|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => $required . '|string|min:6',

        ];
    }

    public function messages()
    {
        return [
//            'full_name.required' => 'Full name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be an valid email',
            'email.unique' => 'Email is already been taken',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters long.',
        ];
    }
}
