<?php

namespace App\Http\Requests\Api\Client\Auth;

use App\Enums\Admin\Admin;
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
            'email' => $req . '|email|exists:users,email,deleted_at,NULL,is_admin,' . Admin::IS_CUSTOMER,
            'google_id' => $req . '|exists:users,google_id,deleted_at,NULL,is_admin,' . Admin::IS_CUSTOMER,
            'apple_id' => $req . '|exists:users,apple_id,deleted_at,NULL,is_admin,' . Admin::IS_CUSTOMER,
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
