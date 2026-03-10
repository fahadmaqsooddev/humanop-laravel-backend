<?php

namespace App\Http\Requests\v4\Api\Client;

use App\Helpers\Helpers;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $assessmentPermission = Helpers::getUser()['plan_name'] === "Freemium" ? 'nullable' : 'required';

        return [
            'bio_privacy' => 'required|in:0,1',
            'personal_quote_connection_privacy' => 'required|in:0,1',
            'personal_quote_public_privacy' => 'required|in:0,1',
            'profile_privacy' => 'required|in:1,2,3',
            'hai_privacy' => 'required|in:1,2,3',
            'core_state' => $assessmentPermission,
            'interval_of_life' => 'nullable|integer|in:1,2',
            'traits' => 'nullable|integer|in:1,2',
            'motivational_driver' => 'nullable|integer|in:1,2',
            'alchemic_boundaries' => 'nullable|integer|in:1,2',
            'communication_style' => 'nullable|integer|in:1,2',
            'perception_of_life' => 'nullable|integer|in:1,2',
            'energy_pool' => 'nullable|integer|in:1,2',
        ];
    }

    public function messages()
    {
        return [
            'bio_privacy.required' => 'Bio privacy field is required.',
            'bio_privacy.in' => 'Bio privacy must be either 0 or 1.',

            'personal_quote_connection_privacy.required' => 'Connection quote privacy is required.',
            'personal_quote_connection_privacy.in' => 'Connection quote privacy must be either 0 or 1.',

            'personal_quote_public_privacy.required' => 'Public quote privacy is required.',
            'personal_quote_public_privacy.in' => 'Public quote privacy must be either 0 or 1.',

            'profile_privacy.required' => 'Profile privacy field is required.',
            'profile_privacy.in' => 'Profile privacy must be one of: 1, 2, or 3.',

            'hai_privacy.required' => 'HAI privacy field is required.',
            'hai_privacy.in' => 'HAI privacy must be one of: 1, 2, or 3.',


            'authentic_traits.required' => 'Authentic traits field is required for your plan.',
            'core_state.required' => 'Core state field is required for your plan.',

            'interval_of_life.in' => 'Interval of life must be 1 or 2.',
            'traits.in' => 'Traits must be 1 or 2.',
            'motivational_driver.in' => 'Motivational drivers must be 1 or 2.',
            'alchemic_boundaries.in' => 'Alchemy must be 1 or 2.',
            'communication_style.in' => 'Communication style must be 1 or 2.',
            'perception_of_life.in' => 'Perception of life must be 1 or 2.',
            'energy_pool.in' => 'Energy pool must be 1 or 2.',
        ];
    }
}
