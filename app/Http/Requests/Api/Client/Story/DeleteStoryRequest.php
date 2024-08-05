<?php

namespace App\Http\Requests\Api\Client\Story;

use App\Helpers\Helpers;
use Illuminate\Foundation\Http\FormRequest;

class DeleteStoryRequest extends FormRequest
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
            'id' => 'required|exists:stories,id,user_id,' . Helpers::getUser()->id,
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Story id is required',
            'id.exists' => 'Story id does not exists',
        ];
    }
}
