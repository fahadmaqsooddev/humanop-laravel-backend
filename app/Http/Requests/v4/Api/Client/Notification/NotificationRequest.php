<?php

namespace App\Http\Requests\v4\Api\Client\Notification;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'notification_status' => 'required|integer|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'notification_status.required' => 'Notification status is required.',
            'notification_status.integer' => 'Notification status must be an integer.',
            'notification_status.in' => 'Notification status must be either 0 or 1.',
        ];
    }
}