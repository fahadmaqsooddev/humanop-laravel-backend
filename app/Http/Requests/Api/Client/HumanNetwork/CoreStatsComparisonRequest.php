<?php

namespace App\Http\Requests\Api\Client\HumanNetwork;

use App\Helpers\Helpers;
use Illuminate\Foundation\Http\FormRequest;

class CoreStatsComparisonRequest extends FormRequest
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
        $plan = Helpers::getUser()['plan_name'];
        $minUsers = $plan === 'Freemium' ? 2 : 3;

        return [
            'user_id'   => "required|array|min:$minUsers",
            'user_id.*' => 'integer|exists:users,id',
        ];
    }

    public function messages()
    {
        $plan = Helpers::getUser()['plan_name'];

        return [
            'user_id.required' => 'The user list is required.',
            'user_id.array' => 'The user ID must be an array.',
            'user_id.max' => $plan === 'Freemium'
                ? 'At least 2 users are required for the Freemium plan.'
                : 'At least 3 users are required for the Core plan.',
            'user_id.*.integer' => 'Each user ID must be an integer.',
            'user_id.*.exists' => 'One or more selected users do not exist.',
        ];
    }


}
