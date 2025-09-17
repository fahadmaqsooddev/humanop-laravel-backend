<?php

namespace App\Http\Requests\Api\CLient\playlist;

use Illuminate\Foundation\Http\FormRequest;

class PlaylistItemTrackRequest extends FormRequest
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
            'item_id'   => 'required',
            'playlist_time' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'playlist_id.required' => 'The playlist is required.',
            'playlist_id.exists'   => 'The selected playlist does not exist.',

            'item_id.required' => 'playlist item is required.',
            'item_time.required' => 'playlist item time is required.',
        ];
    }
}
