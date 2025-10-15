<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateGroupThreadRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100', 'unique:message_threads,name'],
            'group_profile_image' => ['nullable', 'file', 'mimes:jpeg,jpg,png,gif,svg,webp', 'max:204800'], // 200MB
            'member_ids' => ['nullable', 'array'],
            'member_ids.*' => ['integer', 'exists:users,id'],
            'thread_privacy' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The group name is required.',
            'name.string' => 'The group name must be a valid string.',
            'name.max' => 'The group name may not exceed 100 characters.',
            'name.unique' => 'A group with this name already exists.',

            'group_profile_image.file' => 'The group profile image must be a valid file.',
            'group_profile_image.mimes' => 'The group profile image must be an image (jpeg, jpg, png, gif, svg, or webp).',
            'group_profile_image.max' => 'The group profile image size must not exceed 200MB.',

            'member_ids.array' => 'Members must be provided as an array.',
            'member_ids.*.integer' => 'Each member ID must be a valid integer.',
            'member_ids.*.exists' => 'One or more selected members do not exist.',
        ];
    }


}
