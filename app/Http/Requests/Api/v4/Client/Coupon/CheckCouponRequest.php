<?php

namespace App\Http\Requests\Client\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class CheckCouponRequest extends FormRequest
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
            'coupon' => 'nullable|string|max:9',
        ];
    }

    public function messages()
    {
        return [
            'coupon.max' => 'The coupon code should not exceed 9 characters.',
        ];
    }
}
