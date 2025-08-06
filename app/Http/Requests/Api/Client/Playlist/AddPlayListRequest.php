<?php

namespace App\Http\Requests\Api\Client\Playlist;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
class AddPlayListRequest extends FormRequest
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
        $userId = auth()->id();

        return [
            'playlist_id' => [
                'required',
                Rule::exists('playlist', 'id')->where(function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                }),
            ],

            // Require at least one of the three fields
            'resource_item_id' => [
                'required_without_all:shop_item_id,podcast_id',
                'nullable',
                'exists:library_resources,id',
                function ($attribute, $value, $fail) use ($userId) {
                    if ($value) {
                        $exists = DB::table('playlist_log')
                            ->where('user_id', $userId)
                            ->where('playlist_id', request('playlist_id'))
                            ->where('resource_item_id', $value)
                            ->exists();

                        if ($exists) {
                            $fail('You already added this item.');
                        }
                    }
                },
            ],

            'shop_item_id' => [
                'required_without_all:resource_item_id,podcast_id',
                'nullable',
                'exists:humanop_shop_resources,id',
                function ($attribute, $value, $fail) use ($userId) {
                    if ($value) {
                        $exists = DB::table('playlist_log')
                            ->where('user_id', $userId)
                            ->where('playlist_id', request('playlist_id'))
                            ->where('shop_item_id', $value)
                            ->exists();

                        if ($exists) {
                            $fail('You already added this item.');
                        }
                    }
                },
            ],

            'podcast_id' => [
                'required_without_all:resource_item_id,shop_item_id',
                'nullable',
                'exists:podcast,id',
                function ($attribute, $value, $fail) use ($userId) {
                    if ($value) {
                        $exists = DB::table('playlist_log')
                            ->where('user_id', $userId)
                            ->where('playlist_id', request('playlist_id'))
                            ->where('podcast_id', $value)
                            ->exists();

                        if ($exists) {
                            $fail('You already added this item.');
                        }
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'playlist_id.required' => 'Playlist ID is required.',
            'playlist_id.exists' => 'This playlist does not belong to you.',

            'resource_item_id.required_without_all' => 'At least one item (resource, shop, or podcast) is required.',
            'shop_item_id.required_without_all' => 'At least one item (resource, shop, or podcast) is required.',
            'podcast_id.required_without_all' => 'At least one item (resource, shop, or podcast) is required.',

            'resource_item_id.exists' => 'The selected resource does not exist.',
            'shop_item_id.exists' => 'The selected shop item does not exist.',
            'podcast_id.exists' => 'The selected podcast does not exist.',
        ];
    }


}
