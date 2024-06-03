<?php

namespace App\Http\Requests\Admin\Setting;

use Illuminate\Foundation\Http\FormRequest;

class StripeAccountSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected $data;

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
    public function rules()
    {
        return [
            'account_name' => 'required',
            'account_email' => 'required|email',
            'api_key' => 'required',
            'public_key' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'account_name.required' => 'The account name is required.',
            'account_email.required' => 'The account email is required.',
            'account_email.email' => 'Please enter a valid email address for the account email.',
            'api_key.required' => 'The API key is required.',
            'public_key.required' => 'The public key is required.'
        ];
    }
}
