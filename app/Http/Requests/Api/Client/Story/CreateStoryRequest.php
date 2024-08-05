<?php

namespace App\Http\Requests\Api\Client\Story;

use Illuminate\Foundation\Http\FormRequest;

class CreateStoryRequest extends FormRequest
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
            'image' => 'required|image|mimes:jpg,png,jpeg|max:3072'
        ];
    }

    public function messages()
    {
        return [
            'image.required' => 'Image is required',
            'image.image' => 'Story must be type image',
            'image.mimes' => 'Story mimes must be (jpg,png,jpeg)',
            'image.max' => "Maximum image size is 3Mb's"
        ];
    }
}
