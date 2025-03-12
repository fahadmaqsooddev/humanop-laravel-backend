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
            'title' => 'required|max:100',
            'comment' => 'required|max:1000',
            'rating' => 'required|numeric', // Ensure rating is a number
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => 'Rating is required.',
            'rating.numeric' => 'Rating must be a number.',

            'title.required' => 'Please provide a title for your feedback.',
            'title.max' => 'Title must not exceed 100 characters.',

            'comment.required' => 'Please provide a comment in the feedback section.',
            'comment.max' => 'Feedback must not exceed 1000 characters.',
        ];
    }

}
