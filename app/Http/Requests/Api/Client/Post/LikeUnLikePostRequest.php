<?php

namespace App\Http\Requests\Api\Client\Post;

use Illuminate\Foundation\Http\FormRequest;

class LikeUnLikePostRequest extends FormRequest
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
            'post_id' => 'required|exists:posts,id,deleted_at,NULL',
            'type' => 'required|in:like,unlike'
        ];
    }

    public function messages()
    {
        return [
            'post_id.required' => 'Post id is required',
            'post_id.exists' => 'Post id does not exists',
            'type.required' => 'Type is required',
            'type.in' => 'Invalid type. It must be (like or unlike)',
        ];
    }
}
