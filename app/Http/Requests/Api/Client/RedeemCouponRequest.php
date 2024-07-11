<?php

namespace App\Http\Requests\Api\Client;

use Illuminate\Foundation\Http\FormRequest;

class RedeemCouponRequest extends FormRequest
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
            'coupon_code' => 'required|exists:coupons,coupon'
        ];
    }

    public function messages()
    {
        return [
            'coupon_code.required' => "Coupon code is required",
            'coupon_code.exists' => "Coupon Code Invalid"
        ];
    }
}
