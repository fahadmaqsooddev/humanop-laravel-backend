<?php

namespace App\Http\Requests\Admin\ManageCode;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateCodeRequest extends FormRequest
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
            'code' => 'required|string|max:255|unique:codes,code', // Ensure the code is unique
            'type' => 'required|string|max:255',
            'text' => 'required|string',
            'number' => 'required|integer', // Ensure number is an integer
        ];
    }
    

    public function messages()
    {
        return [
            'name.required' => 'The name is required.',
            'public_name.required' => 'The public name is required.',
            'code.required' => 'The code is required.',
            'code.unique' => 'The code must be unique.',
            'type.required' => 'The type is required.',
            'text.required' => 'The text is required.',
            'number.required' => 'The number is required.',
            'number.integer' => 'The number must be an integer.',
        ];
    }
}
