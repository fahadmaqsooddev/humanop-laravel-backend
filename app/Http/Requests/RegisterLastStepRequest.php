<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterLastStepRequest extends FormRequest
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
        $requiredDobAndGender = filter_var(request()->input('is_android', true), FILTER_VALIDATE_BOOLEAN) ? 'required' : 'nullable';

        return [
            'user_id' => 'required',
            'gender' => $requiredDobAndGender . '|in:male,female',
            'date_of_birth' => $requiredDobAndGender . '|date',
            'phone' => 'nullable|max:25',
            'timezone' => 'required|string|max:100',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'User id is required.',
            'time_zone.required' => 'Timezone is required.',
            'gender.required' => 'Gender is required.',
            'gender.in' => 'Gender value must be either male or female.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.date' => 'Date of birth must be a valid date.',
            'phone.max' => 'Phone number cannot exceed 25 characters.',
            'timezone.string' => 'Timezone must be a valid string.',
            'timezone.max' => 'Timezone cannot exceed 100 characters.',
        ];
    }

}
