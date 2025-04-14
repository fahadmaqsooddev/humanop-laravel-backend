<?php

namespace App\Http\Requests\B2B;

use Illuminate\Foundation\Http\FormRequest;

class B2BCompanyNameRequest extends FormRequest
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
            'company_name' => 'required|string|between:1,100|unique:users,company_name',
        ];
    }

    public function messages()
    {
        return [
            'company_name.required' => 'The company name is required.',
            'company_name.string' => 'The company name must be a string.',
            'company_name.between' => 'The company name must be between 1 and 100 characters.',
            'company_name.unique' => 'This company name has already been taken.',
        ];
    }
}
