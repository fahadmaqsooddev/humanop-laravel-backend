<?php

namespace App\Http\Requests\Api\Client;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserImageRequest extends FormRequest
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
            
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg|max:3072'
        ];
    }

    public function messages()
    {
        return [
            
            'profile_image.image' => 'Profile Image must be an image',
            'profile_image.mimes' => 'Profile Image mimes must be (jpg,png,jpeg,gif,svg)',
            'profile_image.max' => "Profile Image maximum size is 3Mb's",

        ];
    }
}
