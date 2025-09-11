<?php

namespace App\Http\Requests\Api\Client\Playlist;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditPlayListRequest extends FormRequest
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
            'playlist_id' => 'required|exists:playlist,id',
            'title' => [
                'nullable',
                'string',
                'max:255',
            ],
            'description' => 'nullable|string|max:10000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:204800',
        ];
    }
}
