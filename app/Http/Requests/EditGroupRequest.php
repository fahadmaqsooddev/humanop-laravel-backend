<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditGroupRequest extends FormRequest
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
            'thread_id' => 'required|exists:message_threads,id',
            'name' => 'nullable|unique:message_threads,name|string|max:255',
            'group_profile_image' => ['nullable', 'file', 'mimes:jpeg,jpg,png,gif,svg,webp', 'max:204800',],
            'thread_privacy' => 'nullable|in:0,1',  // 0 => PUBLIC, 1 => PRIVATE
        ];
    }

    public function messages()
    {
        return [
            'thread_id.required' => 'Group ID is required.',
            'thread_id.exists' => 'The selected group does not exist.',

            'name.required' => 'Group name is required.',
            'name.string' => 'Group name must be a valid string.',
            'name.max' => 'Group name may not be greater than 255 characters.',

            'group_profile_image.file' => 'The group image must be a valid file.',
            'group_profile_image.mimes' => 'Only jpeg, jpg, png, gif, svg, and webp formats are allowed.',
            'group_profile_image.max' => 'The group image size may not exceed 200MB.',

            'thread_privacy.in' => 'Group privacy must be either public or private.',
        ];
    }

}
