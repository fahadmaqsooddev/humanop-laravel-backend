<?php

namespace App\Http\Requests\v4\Api\Client;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\Helpers;

class UpdatePersonalInformationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'nullable|string|max:255',
            'date_of_birth'     => 'required|date',
            'gender'            => 'required|in:male,female',
            'phone'             => 'nullable|string|max:25',
            'timezone'          => 'nullable|timezone',
            'profile_image'     => 'nullable|image|mimes:jpg,png,jpeg|max:3072',
            'excited_connect'   => 'nullable|string|max:255',
            'life_alchemist'    => 'nullable|string|max:255',
            'note'              => 'nullable|string|max:1000',
        ];

        $user = Helpers::getUser();

        if ($user && isset($user['plan_name']) && $user['plan_name'] === 'Premium') {
            $rules['set_daily_tip_time'] = 'required|date_format:h:i A';
        } elseif ($this->filled('set_daily_tip_time')) {
            $rules['set_daily_tip_time'] = 'prohibited';
        }

        return $rules;
    }

    /**
     * Custom validation messages
     */
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
            'timezone.timezone'              => 'Please provide a valid timezone.',
            'set_daily_tip_time.required'    => 'Please set your daily tip time.',
            'set_daily_tip_time.date_format' => 'The daily tip time must be in the format hh:mm AM/PM. Example: 10:30 AM.',
            'set_daily_tip_time.prohibited'  => 'Only Premium users can set a daily tip time.',
            'excited_connect.max'            => 'Excited Connect should not exceed 255 characters.',
            'life_alchemist.max'             => 'Life Alchemist should not exceed 255 characters.',
            'note.max'                       => 'Note should not exceed 1000 characters.',
        ];
    }
}
