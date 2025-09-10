<?php

namespace App\Http\Requests\Api\Client\Playlist;

use Illuminate\Foundation\Http\FormRequest;

class DeletePlayListRequest extends FormRequest
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

            'playlist_resource_item_id' => 'nullable|required_without_all:playlist_shop_item_id,playlist_podcast_item_id|exists:library_resources,id',
            'playlist_shop_item_id'     => 'nullable|required_without_all:playlist_resource_item_id,playlist_podcast_item_id|exists:humanop_shop_resources,id',
            'playlist_podcast_item_id'  => 'nullable|required_without_all:playlist_resource_item_id,playlist_shop_item_id|exists:podcast,id',
        ];
    }

    public function messages()
    {
        return [
            'playlist_id.required' => 'The playlist is required.',
            'playlist_id.exists'   => 'The selected playlist does not exist.',

            'playlist_resource_item_id.required_without_all' => 'At least one of Resource Item, Shop Item, or Podcast Item is required.',
            'playlist_shop_item_id.required_without_all'     => 'At least one of Resource Item, Shop Item, or Podcast Item is required.',
            'playlist_podcast_item_id.required_without_all'  => 'At least one of Resource Item, Shop Item, or Podcast Item is required.',

            'playlist_resource_item_id.exists' => 'The selected resource item does not exist.',
            'playlist_shop_item_id.exists'     => 'The selected shop item does not exist.',
            'playlist_podcast_item_id.exists'  => 'The selected podcast does not exist.',
        ];
    }


}
