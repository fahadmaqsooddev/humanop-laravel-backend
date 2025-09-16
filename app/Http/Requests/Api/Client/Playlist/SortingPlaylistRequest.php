<?php

namespace App\Http\Requests\Api\Client\Playlist;

use Illuminate\Foundation\Http\FormRequest;

class SortingPlaylistRequest extends FormRequest
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
            'playlist_item_ids'   => 'required|array',
        ];
    }

    public function messages()
    {
        return [
            'playlist_id.required' => 'The playlist is required.',
            'playlist_id.exists'   => 'The selected playlist does not exist.',

            'playlist_item_ids.required' => 'At least one playlist item is required.',
            'playlist_item_ids.array'    => 'The playlist items must be in an array format.',
        ];
    }

}
