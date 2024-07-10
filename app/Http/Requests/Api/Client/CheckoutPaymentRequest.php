<?php

namespace App\Http\Requests\Api\Client;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutPaymentRequest extends FormRequest
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
            'price' => 'required|numeric',
            'stripe_token' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a numeric value',
            'stripe_token' => 'Stripe token is required',
        ];
    }
}
