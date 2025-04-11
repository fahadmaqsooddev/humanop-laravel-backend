<?php

namespace App\Http\Requests\B2B;

use Illuminate\Foundation\Http\FormRequest;

class B2BRegisterSecondStep extends FormRequest
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
            'user_id'=>'required',
            'company_name' => 'required|string|unique:users,company_name,NULL,id,deleted_at,NULL',
            'business_sub_stratergy_id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required'=>'User Id IS Required',
            'business_sub_stratergy_id.required' => 'Business Strategy is required.',
            'business_sub_stratergy_id.integer' => 'Business Strategy ID must be an integer.',
             'company_name.required' => 'Company Name is required.',
            'company_name.max' => 'Company Name cannot exceed 50 characters.',
        ];
    }
}
