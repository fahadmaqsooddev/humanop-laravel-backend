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
        $user = Helpers::getUser();
        $plan = strtolower($user->plan);
        $isFreemium = $plan === 'freemium';

        // -----------------------------
        // Common rules for all plans
        // -----------------------------
        $commonRules = [
            'profile_privacy' => 'required|in:1,2,3',
            'hai_privacy' => 'required|in:1,2,3',
        ];

        // -----------------------------
        // Freemium specific rules
        // Only these 3 fields allowed
        // -----------------------------


        if ($isFreemium) {
            $freemiumRules = [
                'core_state' => 'required|integer|in:1,2',
                'interval_of_life' => 'prohibited',
                'traits' => 'prohibited',
                'authentic_traits' => 'prohibited',
                'motivational_driver' => 'prohibited',
                'alchemic_boundaries' => 'prohibited',
                'communication_style' => 'prohibited',
                'perception_of_life' => 'prohibited',
                'energy_pool' => 'prohibited',
                'bio_privacy' => 'prohibited',
                'personal_quote_connection_privacy' => 'prohibited',
                'personal_quote_public_privacy' => 'prohibited',
            ];

            return array_merge($commonRules, $freemiumRules);
        }

        // -----------------------------
        // Premium/Beta rules
        // -----------------------------
        $premiumRules = [
            'bio_privacy' => 'required|in:0,1',
            'personal_quote_connection_privacy' => 'required|in:0,1',
            'personal_quote_public_privacy' => 'required|in:0,1',
            'core_state' => 'prohibited',
            'interval_of_life' => 'required|integer|in:1,2',
            'traits' => 'required|integer|in:1,2',
            'authentic_traits' => 'required|integer|in:1,2',
            'motivational_driver' => 'required|integer|in:1,2',
            'alchemic_boundaries' => 'required|integer|in:1,2',
            'communication_style' => 'required|integer|in:1,2',
            'perception_of_life' => 'required|integer|in:1,2',
            'energy_pool' => 'required|integer|in:1,2',
        ];

        return array_merge($commonRules, $premiumRules);
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

            'core_state.required' => 'Core state field is required for your plan.',
            'authentic_traits.required' => 'Authentic traits field is required for your plan.',
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