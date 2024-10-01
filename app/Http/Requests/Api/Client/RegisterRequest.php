<?php

namespace App\Http\Requests\Api\Client;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => $required . '|email|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => $required . '|string|min:6',
            'phone' => 'required|max:25',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date',
            'ninety_day_intention' => 'nullable|max:1000'
//            'age_range' => 'required|regex:/^\d{1,2}-\d{1,2}$/'
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be an valid email',
            'email.unique' => 'Email is already been taken',
            'gender.required' => 'Gender is required',
            'gender.in' => 'Gender value must be (male/female)',
            'date_of_birth.required' => 'Date of birth is required',
//            'age_range.required' => 'Age is required',
//            'age_range.regex' => 'Invalid age format. Format must be 12-20',
        ];
    }
}
