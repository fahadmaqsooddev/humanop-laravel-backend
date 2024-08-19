<?php

namespace App\Http\Requests\Api\Client\Feedback;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserFeedback extends FormRequest
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
            'comment' => 'required|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'comment.required' => 'Comment is required',
            'comment.max' => 'Comment maximum length is 1000 characters',
        ];
    }
}
