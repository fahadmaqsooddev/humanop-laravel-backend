<?php

namespace App\Http\Requests\Admin\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDiscountRequest extends FormRequest
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
            'discount' => 'required',
            'limit' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'discount.required' => 'discount is required',
            'limit.required' => 'discount limit is required',
        ];
    }
}
