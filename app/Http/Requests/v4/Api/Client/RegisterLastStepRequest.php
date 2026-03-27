<?php

namespace App\Http\Requests\v4\Api\Client;

use Illuminate\Foundation\Http\FormRequest;

class RegisterLastStepRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date',
            'phone' => 'nullable|max:25',
            'timezone' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) {
                    $value = trim($value);
                    $pattern = '/^UTC\/GMT [+-](0[0-9]|1[0-4]):[0-5][0-9] - [A-Za-z_\/]+$/';

                    if (!preg_match($pattern, $value)) {
                        $fail('Invalid timezone format. Example: UTC/GMT +05:00 - Asia/Karachi');
                        return;
                    }

                    [, $named] = explode(' - ', $value, 2);
                    $named = trim($named);
                    
                    if (!in_array($named, timezone_identifiers_list())) {
                        $fail('Invalid timezone name. Example: Asia/Karachi');
                    }
                }
            ],
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'User id is required.',
            'user_id.exists' => 'Selected user does not exist.',

            'gender.required' => 'Gender is required.',
            'gender.in' => 'Gender value must be either male or female.',

            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.date' => 'Date of birth must be a valid date.',

            'phone.max' => 'Phone number cannot exceed 25 characters.',
            'timezone.required' => 'Timezone is required.',

        ];
    }
}
