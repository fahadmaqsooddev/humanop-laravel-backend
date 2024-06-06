<?php

namespace App\Http\Requests\Admin\StripeSetting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStripeRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'account_name' => 'required',
            'account_email' => 'required',
            'api_key' => 'required',
            'public_key' => 'required',
            'amount' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'account_name.required' => 'Account Name is required',
            'account_email.required' => 'Account Email is required',
            'api_key.required' => 'API KEY is required',
            'public_key.required' => 'PUBLIC KEY is required',
            'amount.required' => 'Amount is required',
        ];
    }
}
