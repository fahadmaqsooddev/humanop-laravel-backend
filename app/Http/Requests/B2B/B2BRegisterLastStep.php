<?php

namespace App\Http\Requests\B2B;

use Illuminate\Foundation\Http\FormRequest;

class B2BRegisterLastStep extends FormRequest
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
            'user_id'=>'required|integer',
            'team_department'=>'required',
        ];
    }

    public function messages(){
        return [
            'user_id.required'=>'User Id is Required',
            'team_department.required' => 'Team Department is required.',
        ];
    }
}
