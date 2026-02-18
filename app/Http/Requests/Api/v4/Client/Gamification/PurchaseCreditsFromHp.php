<?php

namespace App\Http\Requests\Api\v4\Client\Gamification;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseCreditsFromHp extends FormRequest
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
            'hp' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'hp.required' => "HP value is required",
            'hp.integer' => "HP must be an integer value",
        ];
    }
}
