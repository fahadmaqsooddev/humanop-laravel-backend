<?php

namespace App\Http\Requests\Api\Client\Post;

use App\Helpers\Helpers;
use Illuminate\Foundation\Http\FormRequest;

class EditCommentRequest extends FormRequest
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
            'comment_id' => 'required|exists:post_comments,id,user_id,' . Helpers::getUser()->id,
            'comment' => 'required|max:300',
        ];
    }

    public function messages()
    {
        return [
            'comment_id.required' => 'Comment id is required',
            'comment_id.exists' => 'Comment id does not exists',
            'comment.required' => 'Comment is required',
            'comment.max' => 'Comment maximum character limit is 300 characters',
        ];
    }
}
