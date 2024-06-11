<?php

namespace App\Http\Requests\Admin\ManageCode;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCodeRequest extends FormRequest
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

            'name' => 'required|string|max:255',
            'public_name' => 'required|string|max:255',
            'code' => 'required|string|max:25',
            'type' => 'required|string|max:25',
            'text' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name is required.',
            'name.max' => 'The name should not exceed 255 characters.',
            'public_name.required' => 'The public name is required.',
            'public_name.max' => 'The public name should not exceed 255 characters.',
            'code.required' => 'The code is required.',
            'code.max' => 'The code should not exceed 25 characters.',
            'type.required' => 'The type is required.',
            'type.max' => 'The type should not exceed 25 characters.',
            'text.required' => 'The text is required.',
        ];
    }
}
