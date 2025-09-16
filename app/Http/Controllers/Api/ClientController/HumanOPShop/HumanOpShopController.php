<?php

namespace App\Http\Controllers\Api\ClientController\HumanOPShop;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\SuggestionItemRequest;
use App\Models\Admin\Resources\ShopCategoryResource;
use App\Models\Admin\SuggestedItem\SuggestedItem;
use App\Models\Client\HumanOpPoints\HumanOpPoints;
use App\Models\Client\PurchasedItems;
use App\Models\Libraries\HumanOpLibraries;
use App\Models\PlaylistLog;
use Illuminate\Support\Facades\DB;
use Stripe\Charge;
use Stripe\Stripe;

class HumanOpShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getShopContents()
    {
        try {

            $allShopResources = ShopCategoryResource::getNotPurchasedShopResources(Helpers::getUser()['id']);

            $formatted = $allShopResources->map(function ($item) {

                $playList = PlaylistLog::getSingleShopItem($item['id']);

                return [
                    'id' => $item->id,
                    'category_name' => $item->shopCategory->name ?? null,
                    'my_playlist' => !empty($playList) ? 1 : 0,
                    'heading' => $item->heading,
                    'description' => $item->description,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'points' => (int) $item->point ?? null,
                    'prices' => (int) $item->price ?? null,
                    'video_url' => isset($item->video_url) ? ($item->video_url['path'] ?? null) : null,
                    'audio_url' => isset($item->audio_url) ? ($item->audio_url['path'] ?? null) : null,
                    'document_url' => isset($item->document_url) ? ($item->document_url['path'] ?? null) : null,
                    'thumbnail_url' => isset($item->thumbnail_url) ? ($item->thumbnail_url['url'] ?? null) : null,
                ];

            });

            return Helpers::successResponse('HumanOp Shop Resource', $formatted);

        } catch (\Exception $e) {

             return Helpers::serverErrorResponse($e->getMessage());

        }

    }

    public function suggestedItems()
    {
//        try {

            $getSuggestedItems = ShopCategoryResource::suggestedItems(Helpers::getUser()['id']);

            $formatted = $getSuggestedItems->map(function ($item) {

                return [
                    'id' => $item->id,
                    'category_name' => $item->shopCategory->name ?? null,
                    'heading' => $item->heading,
                    'description' => $item->description,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'points' => $item['point'] ?? null,
                    'prices' => $item['price'] ?? null,
                    'video_url' => isset($item->video_url) ? ($item->video_url['path'] ?? null) : null,
                    'audio_url' => isset($item->audio_url) ? ($item->audio_url['path'] ?? null) : null,
                    'document_url' => isset($item->document_url) ? ($item->document_url['path'] ?? null) : null,
                    'thumbnail_url' => isset($item->thumbnail_url) ? ($item->thumbnail_url['url'] ?? null) : null,
                ];

            });

            return Helpers::successResponse('HumanOp Shop Suggested Items', $formatted);

//        } catch (\Exception $e) {
//
//            return Helpers::serverErrorResponse($e->getMessage());
//
//        }

    }

    public function suggestedItemCheckout(SuggestionItemRequest $request)
    {
        try {

            DB::beginTransaction();

            $user = Helpers::getUser();

            $itemId = $request['item_id'];

            $buyFrom = $request['buy_from']; // 1 = money, 2 = points

            $type=1;

            $itemAlreadyOwned = HumanOpLibraries::getItem($itemId, $user['id'],$type);

            if (!empty($itemAlreadyOwned)) {

                return Helpers::validationResponse('You have already purchased this item.');

            }

            if ($buyFrom == 1) {

                Stripe::setApiKey(config('cashier.secret'));

                $charge = Charge::create([
                    "amount" => $request['price'] * 100,
                    "currency" => "usd",
                    "source" => 'tok_visa',
                    "description" => "Purchase Suggested Item",
                ]);

                if ($charge && $charge->status === 'succeeded') {

                    HumanOpLibraries::addItem($user['id'], $itemId,$type);

                    $resourceName = ShopCategoryResource::singleLibraryResource($itemId)['heading'];

                    $name = "You have purchased Suggested item {$resourceName}";

                    PurchasedItems::createItem($user['id'], $name, $request['price'], Admin::B2C_PURCHASED_ITEM);

                    DB::commit();

                    return Helpers::successResponse("You have successfully purchased the item.");

                } else {

                    return Helpers::validationResponse("Payment failed. Please try again.");

                }

            } else {

                $userPoints = HumanOpPoints::getUserPoints($user);

                if (($userPoints) && ($userPoints['points'] >= $request['points'])) {

                    HumanOpPoints::deductPoint($user['id'], $request['points']);

                    HumanOpLibraries::addItem($user['id'], $itemId,$type);

                    DB::commit();

                    return Helpers::successResponse("You have successfully redeemed the item using points.");

                } else {

                    return Helpers::validationResponse("Not enough points to redeem this item.");

                }

            }

        } catch (\Exception $e) {

            DB::rollBack();

            return Helpers::serverErrorResponse($e->getMessage());

        }

    }

    public function getLibraries()
    {
        try {

            $shopItems = HumanOpLibraries::getShopBuyItems();
            $libraryItems = HumanOpLibraries::getLibraryBuyItems();

            $shops = [];
            $libraries = [];

            foreach ($shopItems as $item) {

                $playList = PlaylistLog::getSingleShopItem($item['shopItems']['id']);

                $shops[] = [
                    'id' => $item['shopItems']->id,
                    'category_name' => $item['shopItems']->shopCategory->name ?? null,
                    'my_playlist' => !empty($playList) ? 1 : 0,
                    'heading' => $item['shopItems']->heading,
                    'created_at' => $item['shopItems']->created_at,
                    'updated_at' => $item['shopItems']->updated_at,
                    'points' => 0,
                    'prices' => 0,
                    'video_url' => (isset($item['shopItems']->video_url) && !empty($item['shopItems']->video_url)) ? $item['shopItems']->video_url : null,
                    'audio_url' => (isset($item['shopItems']->audio_url) && !empty($item['shopItems']->audio_url)) ? $item['shopItems']->audio_url : null,
                    'document_url' => isset($item['shopItems']->document_url) ?? null,
                    'thumbnail_url' => isset($item['shopItems']->thumbnail_url) ? ($item['shopItems']->thumbnail_url['url'] ?? null) : null,
                ];

            }
            foreach ($libraryItems as $libraryItem) {

                $playList = PlaylistLog::getSingleResourceItem($libraryItem['libraryItems']['id']);

                $libraries[] = [
                    'id' => $libraryItem['libraryItems']->id,
                    'heading' => $libraryItem['libraryItems']->heading,
                    'my_playlist' => !empty($playList) ? 1 : 0,
                    'slug' => $libraryItem['libraryItems']->slug,
                    'description' => $libraryItem['libraryItems']->description,
                    'content' => $libraryItem['libraryItems']->content,
                    'relevance' => $libraryItem['libraryItems']->relevance,
                    'photo_url' => $libraryItem['libraryItems']->photo_url ?? null,
                    'video_url' => $libraryItem['libraryItems']->video_url ?? null,
                    'audio_url' => $libraryItem['libraryItems']->audio_url ?? null,
                    'thumbnail_url' => $libraryItem['libraryItems']->thumbnail_url ?? null,
                    'resource_category_name' => optional($libraryItem['libraryItems']->resourceCategory)->name,
                    'library_permission_name' => match(optional($libraryItem['libraryItems']->libraryPermissions)->permission) {
                        1 => 'Freemium',
                        2 => 'Core',
                        3 => 'Premium',
                        4 => 'HP Look', // or whatever label you want for permission 4
                        default => 'null',
                    },
                    'price' => 0,
                    'point' => 0,
                ];
            }
            $libraries = [
                'humanOp_shop_items' => $shops,
                'tools_training_items' => $libraries
            ];

            return Helpers::successResponse('HumanOp Shop Libraries', $libraries);

        } catch (\Exception $e) {

            return Helpers::serverErrorResponse($e->getMessage());

        }

    }

}
