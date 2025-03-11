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
        $required = (request()->input('google_id') || request()->input('apple_id')) ? 'nullable' : 'required';

        return [
            'full_name' => 'required|string|max:255',
//            'email' => $required . '|email|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => $required . '|string|min:6',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date',
            'phone' => 'nullable|string|max:25',
            'company_name' => 'required|string|max:50',
            'timezone' => 'required|string',
            'business_sub_stratergy_id' => 'required|integer',
            'work_email'=>'required',
            'intention_option_id'=>'required'
        ];
    }

    public function messages()
    {
        return [

            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters long.',
            'gender.required' => 'Gender is required.',
            'gender.in' => 'Gender value must be either male or female.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.date' => 'Date of birth must be a valid date.',
            'phone.max' => 'Phone number cannot exceed 25 characters.',
            'company_name.required' => 'Company Name is required.',
            'company_name.max' => 'Company Name cannot exceed 50 characters.',
            'timezone.required' => 'Timezone is required.',
            'work_email.required' => 'work email is required.',
            'work_email.required' => 'work email is required.',
            'intention_option_id.required' => 'intention option ids  is required.',
            'business_sub_stratergy_id.required' => 'Business Strategy is required.',
            'business_sub_stratergy_id.integer' => 'Business Strategy ID must be an integer.'
        ];
    }

}
