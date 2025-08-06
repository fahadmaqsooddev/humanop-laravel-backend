<?php

namespace App\Http\Requests\Api\Client\Playlist;

use Illuminate\Foundation\Http\FormRequest;
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
            'resource_item_id' => 'required_without:shop_item_id|nullable|exists:library_resources,id',
            'shop_item_id' => 'required_without:resource_item_id|nullable|exists:humanop_shop_resources,id',
        ];
    }


    public function messages()
    {
        return [
            'playlist_id.required' => 'Playlist ID is required.',
            'playlist_id.exists' => 'The selected playlist does not belong to you.',

            'resource_item_id.required_without' => 'The resource item is required when no shop item is provided.',
            'resource_item_id.exists' => 'The selected resource item does not exist.',

            'shop_item_id.required_without' => 'The shop item is required when no resource item is provided.',
            'shop_item_id.exists' => 'The selected shop item does not exist.',
        ];
    }


}
