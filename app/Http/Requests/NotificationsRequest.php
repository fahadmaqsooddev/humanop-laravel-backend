<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class NotificationsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guard('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'status' => 'nullable|in:0,1',
            'pagination' => 'nullable|in:true,false',
            'per_page' => 'nullable|integer|min:1|max:100'
        ];
    }

    /**
     * Optional: customize validation messages
     */
    public function messages(): array
    {
        return [
            'status.in'       => 'The status field must be 0 (unread) or 1 (read).',
            'pagination.in' => 'The pagination field must be "true" or "false" as string.',
            'per_page.integer'=> 'The per_page field must be a number.',
            'per_page.min'    => 'The per_page field must be at least 1.',
            'per_page.max'    => 'The per_page field may not be greater than 100.',
        ];
    }

}
