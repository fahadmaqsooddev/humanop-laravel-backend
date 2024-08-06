<?php

namespace App\Http\Requests\Api\Client\Post;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
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
            'description' => 'required|max:1000',
            'post_image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:3072',
        ];
    }

    public function messages()
    {
        return [
            'description.required' => 'Post description is required',
            'description.max' => 'Post description can not be more than 1000 characters',
            'post_image.image' => 'Post must be an image',
            'post_image.mimes' => 'Post image mimes must be (jpg,png,jpeg,gif,svg)',
            'post_image.max' => "Post image maximum size is 3Mb's",
        ];
    }
}
