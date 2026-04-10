<?php

namespace App\Http\Requests\v4\Api\Client\EnergyShield;

use Illuminate\Foundation\Http\FormRequest;

class IngestSamplesRequest extends FormRequest
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
            'samples' => ['required', 'array', 'min:1'],

            'samples.*.metric' => [
                'required',
                'string',
                'in:' . implode(',', config('humanop.allowed_metrics'))
            ],

            'samples.*.value' => ['required', 'numeric'],

            'samples.*.recorded_at' => ['required', 'date'],

            'samples.*.source' => ['nullable', 'string'],

            'samples.*.metadata' => ['nullable', 'array'],

            'locations' => ['nullable', 'array'],

            'locations.*.place_id' => ['required_with:locations', 'string'],

            'locations.*.latitude' => ['nullable', 'numeric'],

            'locations.*.longitude' => ['nullable', 'numeric'],

            'locations.*.recorded_at' => ['required_with:locations', 'date'],

            'locations.*.metadata' => ['nullable', 'array'],
        ];
    }

    public function messages()
    {
        return [

            'samples.required' => 'Samples field is required.',
            'samples.array' => 'Samples must be an array.',
            'samples.min' => 'At least one sample is required.',

            'samples.*.metric.required' => 'Metric is required for each sample.',
            'samples.*.metric.string' => 'Metric must be a valid string.',
            'samples.*.metric.in' => 'Metric must be one of the allowed metrics.',

            'samples.*.value.required' => 'Value is required for each sample.',
            'samples.*.value.numeric' => 'Value must be numeric.',

            'samples.*.recorded_at.required' => 'Recorded date is required.',
            'samples.*.recorded_at.date' => 'Recorded date must be a valid date.',

            'samples.*.source.string' => 'Source must be a valid string.',

            'samples.*.metadata.array' => 'Metadata must be an array.',

            'locations.*.place_id.required_with' => 'Place ID is required for each location.',
            'locations.*.recorded_at.required_with' => 'Recorded date is required for each location.',
        ];
    }
}
