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
            'date_of_birth' => 'required|date',
//            'age_range' => 'required|regex:/^\d{1,2}-\d{1,2}$/',
            'gender' => 'required|in:male,female',
            'phone' => 'required|max:25',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg|max:3072'
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'First name is required',
//            'age_range.required' => 'Age is required',
//            'age_range.regex' => 'Invalid age format. Age range must be - separated',
            'date_of_birth.required' => 'Date of birth is required',
            'gender.required' => 'Gender is required',
            'gender.in' => 'Invalid gender. Gender must be male or female',
            'phone.required' => 'Phone is required',
            'profile_image.image' => 'Profile Image must be an image',
            'profile_image.mimes' => 'Profile Image mimes must be (jpg,png,jpeg,gif,svg)',
            'profile_image.max' => "Profile Image maximum size is 3Mb's",
        ];
    }
}
