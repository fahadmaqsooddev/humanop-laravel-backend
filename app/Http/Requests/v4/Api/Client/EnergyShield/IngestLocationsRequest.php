<?php

namespace App\Http\Requests\v4\Api\Client\EnergyShield;

use Illuminate\Foundation\Http\FormRequest;

class IngestLocationsRequest extends FormRequest
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

    public function rules()
    {
        return [
            'locations' => ['required', 'array', 'min:1'],

            'locations.*.place_id' => ['required', 'string'],

            'locations.*.recorded_at' => ['required', 'date'],

            'locations.*.latitude' => ['nullable', 'numeric'],

            'locations.*.longitude' => ['nullable', 'numeric'],

            'locations.*.metadata' => ['nullable', 'array'],
        ];
    }

    public function messages()
    {
        return [

            'locations.required' => 'Locations field is required.',
            'locations.array' => 'Locations must be an array.',
            'locations.min' => 'At least one location is required.',

            'locations.*.place_id.required' => 'Place ID is required for each location.',
            'locations.*.place_id.string' => 'Place ID must be a valid string.',

            'locations.*.recorded_at.required' => 'Recorded date is required for each location.',
            'locations.*.recorded_at.date' => 'Recorded date must be a valid date.',

            'locations.*.latitude.numeric' => 'Latitude must be a numeric value.',

            'locations.*.longitude.numeric' => 'Longitude must be a numeric value.',

            'locations.*.metadata.array' => 'Metadata must be an array.',
        ];
    }
}
