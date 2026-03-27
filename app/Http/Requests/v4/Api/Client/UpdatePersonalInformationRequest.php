<?php

namespace App\Http\Requests\v4\Api\Client;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\Helpers;

class UpdatePersonalInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'nullable|string|max:255',
            'date_of_birth'   => 'required|date',
            'gender'          => 'required|in:male,female',
            'phone'           => 'nullable|string|max:25',
            'timezone' => [
                'nullable',
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
            'profile_image'   => 'nullable|image|mimes:jpg,png,jpeg|max:3072',
            'nickname'        => 'nullable|string|max:100',
            'personal_quote'  => 'nullable|string|max:255',
            'bio'             => 'nullable|string|max:2000',
        ];

        $user = Helpers::getUser();

        if ($user && isset($user['plan_name']) && $user['plan_name'] === 'Premium') {
            $rules['set_daily_tip_time'] = 'required|date_format:h:i A';
        } elseif ($this->filled('set_daily_tip_time')) {
            $rules['set_daily_tip_time'] = 'prohibited';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'first_name.required'            => 'First name is required.',
            'date_of_birth.required'         => 'Date of birth is required.',
            'gender.required'                => 'Gender is required.',
            'gender.in'                      => 'Invalid gender. It must be either male or female.',
            'profile_image.image'            => 'Profile Image must be an image.',
            'profile_image.mimes'            => 'Profile Image must be of type jpg, png, jpeg.',
            'profile_image.max'              => 'Profile Image maximum size is 3MB.',
            'phone.max'                      => 'Phone number should not exceed 25 characters.',
            'set_daily_tip_time.required'    => 'Please set your daily tip time.',
            'set_daily_tip_time.date_format' => 'The daily tip time must be in the format hh:mm AM/PM. Example: 10:30 AM.',
            'set_daily_tip_time.prohibited'  => 'Only Premium users can set a daily tip time.',
            'nickname.max'                   => 'Nickname should not exceed 100 characters.',
            'personal_quote.max'             => 'Personal quote should not exceed 255 characters.',
            'bio.max'                        => 'Bio should not exceed 2000 characters.',
        ];
    }
}
