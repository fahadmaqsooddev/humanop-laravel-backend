<?php

namespace App\Http\Requests\Client\Register;

use Illuminate\Foundation\Http\FormRequest;

class SmsRequest extends FormRequest
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
                'user_id' => 'required|exists:users,id',
                'phone' => 'required',
            ];
        }

        public function messages()
        {
            return [
                'user_id.required' => 'The user ID is required.',
                'user_id.exists' => 'The selected user does not exist.',
                'phone.required' => 'The phone number is required.'
            ];
        }
}
