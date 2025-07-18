<?php

namespace App\Http\Requests\Api\Client;

use Illuminate\Foundation\Http\FormRequest;

class LibraryResourceSuggestionItemRequest extends FormRequest
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
        $buyFrom = $this->input('buy_from');
        $priceRule = $buyFrom == 1 ? 'required|numeric|min:1' : 'nullable';
        $pointRule = $buyFrom == 1 ? 'nullable' : 'required|numeric|min:1';

        return [
            'item_id' => 'required|exists:library_resources,id',
            'buy_from' => 'required|in:1,2',
            'price' => $priceRule,
            'points' => $pointRule,
        ];
    }

    public function messages()
    {
        return [
            'item_id.required' => 'The item ID is required.',
            'item_id.exists' => 'The selected humanOp Library item does not exist.',
            'buy_from.required' => 'Please select a purchase method (price or points).',
            'buy_from.in' => 'Invalid purchase method selected.',
            'price.required' => 'Price is required when buying with currency.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price must be at least 1.',
            'points.required' => 'Points are required when buying with points.',
            'points.numeric' => 'Points must be a valid number.',
            'points.min' => 'Points must be at least 1.',
        ];
    }


}
