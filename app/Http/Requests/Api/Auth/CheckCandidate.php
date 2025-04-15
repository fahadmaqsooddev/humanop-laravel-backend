<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class CheckCandidate extends FormRequest
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
            'token'=>'required',
            'company_name'=>'required',
            'prefer' => 'required'
        ];
    }
    public function messages(){
        return[
            'token.required'=>'Token is required',
            'company_name.required'=>'Company name is required',
            'prefer.required'=>'Prefer is required',
        ];
    }
}
