<?php

namespace App\Http\Requests\Api\Client\Notification;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
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
            'notification_id' => 'nullable|array',
            'notification_id.*' => 'integer|exists:notifications,id',
        ];
    }

    public function messages()
    {
        return [
//            'notification_id.required' => 'Notification ID list is required.',
            'notification_id.array' => 'Notification ID must be an array.',
            'notification_id.*.integer' => 'Each notification ID must be an integer.',
            'notification_id.*.exists' => 'One or more selected notification IDs do not exist.',
        ];
    }

}
