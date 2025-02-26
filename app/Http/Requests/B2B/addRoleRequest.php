<?php

namespace App\Http\Requests\B2B;

use Illuminate\Foundation\Http\FormRequest;

class addRoleRequest extends FormRequest
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
            'name' => 'required|max:100',
            'role_template_id' => 'required',
            'tag1' => 'required|max:100',
            'tag2' => 'required|max:100',
            'tag3' => 'required|max:100',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Role Name is require',
            'name.max' => 'Role Name must not exceed 100 character Limit',
            'role_template_id.required' => 'Role Id is require',
            'tag1.required' => 'Role Tag 1 is require',
            'tag2.required' => 'Role Tag 2 is require',
            'tag3.required' => 'Role Tag 3 is require',
        ];
    }
}
