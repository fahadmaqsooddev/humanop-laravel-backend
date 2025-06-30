<?php

namespace App\Http\Controllers\Api\ClientController\HumanOPShop;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Admin\Resources\ShopCategoryResource;
use Illuminate\Http\Request;

class HumanOpShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        try {

            $allShopResources = ShopCategoryResource::getResources();

            $formatted = $allShopResources->map(function ($item) {

                return [
                    'id' => $item->id,
                    'category_name' => $item->shopCategory->name ?? null,
                    'heading' => $item->heading,
                    'description' => $item->description,
                    'content' => $item->content,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'buy_from' => $item->buy_from == 1 ? 'Price' : 'Point',
                    'photo_url' => isset($item->photo_url) ? ($item->photo_url['url'] ?? null) : null,
                    'video_url' => isset($item->video_url) ? ($item->video_url['path'] ?? null) : null,
                    'audio_url' => isset($item->audio_url) ? ($item->audio_url['path'] ?? null) : null,
                ];

            });

            return Helpers::successResponse('HumanOp Shop Resource', $formatted);

        } catch (\Exception $e) {
             return Helpers::serverErrorResponse($e->getMessage());
        }
    }
}
