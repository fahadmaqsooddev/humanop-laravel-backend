<?php

namespace App\Http\Requests\Api\Client\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpCodeRequest extends FormRequest
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
            'code' => 'required|integer',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'Code is required.',
            'code.integer' => 'Code must be integer.',
            'user_id.required' => 'User id is required.',
            'user_id.exists' => 'User does not exists.',
        ];
    }
}
