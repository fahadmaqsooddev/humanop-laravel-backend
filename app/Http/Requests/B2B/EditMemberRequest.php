<?php

namespace App\Http\Requests\B2B;

use Illuminate\Foundation\Http\FormRequest;

class EditMemberRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required',
            'password'=>'required',
            'gender'=> 'required',
            'timezone'=>'required',
            'member_id'=>'required',


        ];
    }
    public function message(){
        return [
            'first_name.required' => 'First Name is required',
            'last_name.required' => 'Last Name is required',
            'phone.required' => 'Phone is required',
            'password.required' => 'Password is required',
            'gender.required' => 'Gender is required',
            'timezone.required' => 'Timezone is required',
           'member_id.required' => 'Member ID is required',
        ];
    }
}
