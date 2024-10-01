<?php

namespace App\Http\Requests\Api\Client\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SocialLoginRequest extends FormRequest
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

        $req = 'required';

        if (request()->has('email') && !empty(request()->input('email'))){

            $req = 'nullable';

        }else if (request()->has('google_id') && !empty(request()->input('google_id'))){

            $req = 'nullable';

        }else if (request()->has('apple_id') && !empty(request()->input('apple_id'))){

            $req = 'nullable';
        }

        return [
            'email' => $req . '|email|exists:users,email,deleted_at,NULL',
            'google_id' => $req,
            'apple_id' => $req,
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email',
            'email.exists' => 'Email does not exists',
            'google_id.required' => 'Google/Apple id is required',
            'apple_id.required' => 'Google/Apple id is required',
        ];
    }
}
