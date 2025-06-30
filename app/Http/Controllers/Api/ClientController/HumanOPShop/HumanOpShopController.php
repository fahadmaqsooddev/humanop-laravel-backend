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
                    'heading' => $item->heading,
                    'slug' => $item->slug,
                    'upload_id' => $item->upload_id,
                    'humanop_shop_category_id' => $item->humanop_shop_category_id,
                    'description' => $item->description,
                    'content' => $item->content,
                    'source_id' => $item->source_id,
                    'source_url' => $item->source_url,
                    'embed_link' => $item->embed_link,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'buy_from' => $item->buy_from == 1 ? 'Price' : 'Point',
                    'category_name' => $item->shopCategory->name ?? null,
                ];
            });

            return Helpers::successResponse('HumanOp Shop Resource', $formatted);

        } catch (\Exception $e) {
             return Helpers::serverErrorResponse($e->getMessage());
        }
    }
}
