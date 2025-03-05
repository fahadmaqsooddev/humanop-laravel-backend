<?php

namespace App\Http\Requests\B2B;

use Illuminate\Foundation\Http\FormRequest;

class B2BSupportRequest extends FormRequest
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
            'title' => 'required|string|',
            'description' => 'required|max:1000',
            'image'=>'required|image|mimes:jpg,png,jpeg|max:3072',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is required',
            'description.required' => 'Description is required',
            'description.max' => 'Description should not exceed 1000 characters',
            'image.required' => 'Image is required',
            'image.image' => 'Profile Image must be an image',
            'image.mimes' => 'Profile Image mimes must be (jpg,png,jpeg,gif,svg)',
            'image.max' => "Profile Image maximum size is 3Mb's",
           
        ];
    }
}
