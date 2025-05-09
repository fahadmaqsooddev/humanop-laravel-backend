<?php

namespace App\Http\Requests\Client\ProfileAccess;

use Illuminate\Foundation\Http\FormRequest;

class HaiAccessRequest extends FormRequest
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
            'change_hai_access' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'change_hai_access.required' => ' HAi access key is required.',
        ];
    }
}
