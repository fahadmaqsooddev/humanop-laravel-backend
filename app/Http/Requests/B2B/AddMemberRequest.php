<?php

namespace App\Http\Requests\B2B;

use Illuminate\Foundation\Http\FormRequest;

class AddMemberRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name'=>'required|string|max:255',
                        'email' => 'required|email',
                        'password' =>  'required|string|min:6',
                     'phone'=>'required'     
        ];
    }
    public function messages(){
        return [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email',
            
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters long.',
            'phone.required' => 'Phone number is required'
        ];  // TODO: Add custom error messages for each validation rule.
    }
}
