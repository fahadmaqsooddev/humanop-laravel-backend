<?php

namespace App\Http\Requests\Api\Client;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
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
            'age_range' => 'required|regex:/^\d{1,2}-\d{1,2}$/',
            'gender' => 'required|in:male,female',
            'phone' => 'required|max:25',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'First name is required',
            'age_range.required' => 'Age is required',
            'age_range.regex' => 'Invalid age format. Age range must be - separated',
            'gender.required' => 'Gender is required',
            'gender.in' => 'Invalid gender. Gender must be male or female',
            'phone.required' => 'Phone is required',
        ];
    }
}
