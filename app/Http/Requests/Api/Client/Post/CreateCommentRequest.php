<?php

namespace App\Http\Requests\Api\Client\Post;

use Illuminate\Foundation\Http\FormRequest;

class CreateCommentRequest extends FormRequest
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
            'comment' => 'required|max:300',
        ];
    }

    public function messages()
    {
        return [
            'post_id.required' => 'Post id is required',
            'post_id.exists' => 'Post id does not exists',
            'comment.required' => 'Comment is required',
            'comment.max' => 'Comment maximum character limit is 300 characters',
        ];
    }
}
