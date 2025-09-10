<?php

namespace App\Http\Requests\Api\Client\Playlist;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewPlaylistRequest extends FormRequest
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
        $userId = auth()->id(); // Get the currently authenticated user's ID

        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('playlist', 'title')->where(function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                }),
            ],
            'description' => 'required|string|max:10000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:204800',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The title is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'title.unique' => 'A playlist with this title already exists.',
            'description.required' => 'The description is required.',
            'description.string' => 'The description must be a string.',
            'description.max' => 'The description is too long.',
        ];
    }

}
