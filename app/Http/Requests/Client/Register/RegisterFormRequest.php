<?php

namespace App\Http\Requests\Client\Register;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RegisterFormRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|max:255',
            'age_range' => 'required|regex:/^\d{1,2}-\d{1,2}$/',
            'gender' => 'required|string',
            'phone' => 'required|string|max:25'
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'The first name is required.',
            'first_name.max' => 'The first name should not exceed 255 characters.',
            'last_name.required' => 'The last name is required.',
            'last_name.max' => 'The last name should not exceed 255 characters.',
            'email.required' => 'The email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'The email should not exceed 255 characters.',
            'email.unique' => 'The email address is already registered.',
            'age_range.required' => 'The age range is required.',
            'age_range.regex' => 'Please enter a valid age range.',
            'gender.required' => 'The gender is required.',
            'phone.required' => 'The phone number is required.',
            'phone.max' => 'The phone number should not exceed 25 characters.',
        ];
    }
}
