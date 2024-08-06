<?php

namespace App\Http\Requests\Api\Client\Post;

use Illuminate\Foundation\Http\FormRequest;

class EditPostRequest extends FormRequest
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
            'id' => 'required|exists:posts,id,deleted_at,NULL',
            'description' => 'required|max:1000',
            'post_image' => 'nullable|image|mimes:jpg,png,jpeg|max:3072'
        ];
    }
}
