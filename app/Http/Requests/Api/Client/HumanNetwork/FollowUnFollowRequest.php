<?php

namespace App\Http\Requests\Api\Client\HumanNetwork;

use Illuminate\Foundation\Http\FormRequest;

class FollowUnFollowRequest extends FormRequest
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
            'follow_id' => 'required|exists:users,id,deleted_at,NULL',
            'type' => 'required|in:follow,unfollow',
        ];
    }
}
