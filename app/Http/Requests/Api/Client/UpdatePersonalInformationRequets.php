<?php

namespace App\Http\Requests\Api\Client;

use App\Helpers\Helpers;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonalInformationRequets extends FormRequest
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
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'phone' => 'nullable|max:25',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg|max:3072',
        ];


        if (Helpers::getUser()['plan_name'] == 'Premium') {
            $rules['set_daily_tip_time'] = 'required|date_format:h:i A';
        } else {
            // Trigger custom error if user tries to set time without a paid plan
            if (!empty($this->set_daily_tip_time)) {
                $rules['set_daily_tip_time'] = 'prohibited';
            }
        }

        return $rules;
    }



    public function messages()
    {
        return [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'date_of_birth.required' => 'Date of birth is required',
            'gender.required' => 'Gender is required',
            'gender.in' => 'Invalid gender. Gender must be male or female',
            'profile_image.image' => 'Profile Image must be an image',
            'profile_image.mimes' => 'Profile Image mimes must be (jpg,png,jpeg,gif,svg)',
            'profile_image.max' => "Profile Image maximum size is 3Mb's",
            'phone.max' => 'The phone number should not exceed 25 characters.',
            'set_daily_tip_time.required' => 'Please set the daily tip time.',
            'set_daily_tip_time.date_format' => 'The daily tip time must be in the format hh:mm AM/PM. Example: 10:30 AM',
            'set_daily_tip_time.prohibited' => 'Only paid users can set daily tip time.',

        ];
    }
}
