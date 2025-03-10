<?php

namespace App\Http\Requests\B2B;

use Illuminate\Foundation\Http\FormRequest;

class updateB2BProfileRequest extends FormRequest
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
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'timezone' => 'required',
            'phone' => 'nullable|max:25',
            'company_name' => 'required|string|max:50',
            'password'=>'nullable',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'date_of_birth.required' => 'Date of birth is required',
            'gender.required' => 'Gender is required',
            'timezone.required' => 'Timezone is required',
            'gender.in' => 'Invalid gender. Gender must be male or female',
            'phone.max' => 'The phone number should not exceed 25 characters.',
            'company_name.required' => 'Company Name is required.',
            'company_name.max' => 'Company Name cannot exceed 50 characters.',

        ];
    }
}
