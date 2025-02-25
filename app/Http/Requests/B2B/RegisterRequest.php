<?php

namespace App\Http\Requests\B2B;

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
        // $requiredDobAndGender = filter_var(request()->input('is_android', true), FILTER_VALIDATE_BOOLEAN) ? 'required' : 'nullable';
        return [
                       'full_name' => 'required|string|max:255',
                        'email' => $required . '|email|unique:users,email,NULL,id,deleted_at,NULL',
                        'password' => $required . '|string|min:6',
                        'gender' => 'required|in:male,female',
                        'date_of_birth' => 'required|date',
                        'phone' => 'required|max:25',
                        'company_name' => 'required|max:50',
                        'timezone'=>'required',
            
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
            'gender.required' => 'Gender is required.',
            'gender.in' => 'Gender value must be either male or female.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.date' => 'Date of birth must be a valid date.',
            'phone.required' => 'Phone number required.',
            'phone.max' => 'Phone number cannot exceed 25 characters.',
            'company_name.max' => 'Company Name cannot exceed 50 characters.',
            'timezone.required'=>'Timezone is required.',
        ];
    }
}
