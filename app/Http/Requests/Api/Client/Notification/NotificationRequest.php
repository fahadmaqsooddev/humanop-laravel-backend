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
            'notification_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'notification_id.required' => 'Notification Id is required',
        ];
    }
}
