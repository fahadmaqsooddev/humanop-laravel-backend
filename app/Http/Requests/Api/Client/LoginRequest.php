<?php

namespace App\Http\Requests\Api\Client;

use App\Enums\Admin\Admin;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required|email|exists:users,email,deleted_at,NULL,is_admin,' . Admin::IS_CUSTOMER,
            'password' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'email.exists' => 'Email does not exists in our records',
        ];
    }
}
