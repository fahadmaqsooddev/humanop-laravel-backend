<?php

namespace App\Http\Requests\Admin\Setting;

use Illuminate\Foundation\Http\FormRequest;

class PasswordSettingRequest extends FormRequest
{
    protected $data;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function __construct($data = null)
    {
        parent::__construct();
        $this->data = $data;
    }

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){

        return [
            'current_password' => 'required',
            'password' => [
                'required',
                'string',
                'min:6',
                'regex:/[!@#$%^&*(),.?":{}|<>]/', // At least one special character
                'regex:/[0-9].*[0-9]/',           // At least two numbers
                'different:current_password',
            ],
            'confirm_password' => 'required|same:password',
        ];
    }
    public function messages(){
        return [
            'current_password.required' => 'The current password is required.',
            'password.required' => 'The new password is required.',
            'password.min' => 'The new password should be at least 6 characters long.',
            'password.regex' => 'The new password should contain at least one special character and two numbers.',
            'password.different' => 'The new password must be different from the current password.',
            'confirm_password.required' => 'Please confirm the new password.',
            'confirm_password.same' => 'The password confirmation does not match the new password.',
        ];
    }

}
