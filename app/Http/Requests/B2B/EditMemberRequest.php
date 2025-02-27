<?php

namespace App\Http\Requests\B2B;

use Illuminate\Foundation\Http\FormRequest;

class EditMemberRequest extends FormRequest
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
            'last_name' => 'required|string|max:255',
            // 'email' => 'required|emailunique|accepted|max:255',
           
            'phone' => 'required',
            'member_id'=>'required',
            'email'=>''

        ];
    }
    public function messages(){
        return [
            'first_name.required' => 'The first name is required.',
            'first_name.max' => 'The first name should not exceed 255 characters.',
            'last_name.required' => 'The last name is required.',
            'last_name.max' => 'The last name should not exceed 255 characters.',
            'email.required' => 'The email is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'The password is required.',
            'password.min' => 'The password should be at least 6 characters long.',
            'phone.required' => 'The phone number is required.'
        ];  // TODO: Add more specific error messages for each field.  // Example: 'phone.required' => 'The phone number is required.'  // The rest of the error messages remain the same as the original request
    }
}
