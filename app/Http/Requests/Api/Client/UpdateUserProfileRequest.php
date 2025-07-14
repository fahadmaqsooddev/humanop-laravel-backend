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
            'full_name' => 'required',
            'life_alchemist' => 'required|string|max:255',
            'excited_connect' => 'required|string|max:255',
            'note' => 'required|string|max:255',
            'tag_line' => 'required',
            'profile_status' => 'required',
            'hai_status' => 'required',
            'profile_privacy' => 'required',
            'hai_privacy' => 'required',
            'interval_of_life' => 'required',
            'traits' => 'required',
            'motivational_driver' => 'required',
            'alchemic_boundaries' => 'required',
            'communication_style' => 'required',
            'perception_of_life' => 'required',
            'energy_pool' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => 'Full name is required.',
            'life_alchemist.required' => 'Please provide your role as a life alchemist.',
            'life_alchemist.string' => 'Life alchemist must be a valid string.',
            'life_alchemist.max' => 'Life alchemist cannot exceed 255 characters.',
            'excited_connect.required' => 'Tell us what excites you to connect.',
            'excited_connect.string' => 'Excited to connect must be a string.',
            'excited_connect.max' => 'Excited to connect cannot exceed 255 characters.',
            'note.required' => 'Please write a note.',
            'note.string' => 'Note must be a valid string.',
            'note.max' => 'Note cannot exceed 255 characters.',
            'tag_line.required' => 'A tag line is required.',
            'profile_status.required' => 'Profile status is required.',
            'hai_status.required' => 'HAI status is required.',
            'profile_privacy.required' => 'Profile privacy setting is required.',
            'hai_privacy.required' => 'HAI privacy setting is required.',
            'interval_of_life.required' => 'Interval of life is required.',
            'traits.required' => 'Please specify your traits.',
            'motivational_driver.required' => 'Please specify your motivational driver.',
            'alchemic_boundaries.required' => 'Alchemic boundaries are required.',
            'communication_style.required' => 'Communication style is required.',
            'perception_of_life.required' => 'Perception of life is required.',
            'energy_pool.required' => 'Energy pool is required.',
        ];
    }

}
