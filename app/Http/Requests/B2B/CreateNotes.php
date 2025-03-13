<?php

namespace App\Http\Requests\B2B;

use Illuminate\Foundation\Http\FormRequest;

class CreateNotes extends FormRequest
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

            'user_id'=>'required',
            'note_id'=>'nullable',
            'note'=>'required'
        ];
    }
    public function messages()
    {
        return [
            'user_id.required' => 'user id is required',
            'note.required' => 'note is required',
        ];
    }


}
