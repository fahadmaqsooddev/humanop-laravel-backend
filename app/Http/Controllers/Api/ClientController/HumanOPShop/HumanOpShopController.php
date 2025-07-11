<?php

namespace App\Http\Controllers\Api\ClientController\HumanOPShop;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\SuggestionItemRequest;
use App\Models\Admin\Resources\ShopCategoryResource;
use App\Models\Client\HumanOpPoints\HumanOpPoints;
use App\Models\Libraries\HumanOpLibraries;
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

            $allShopResources = ShopCategoryResource::getResources();

            $formatted = $allShopResources->map(function ($item) {

                return [
                    'id' => $item->id,
                    'category_name' => $item->shopCategory->name ?? null,
                    'heading' => $item->heading,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'buy_from' => $item->buy_from == 1 ? 'Price' : 'Point',
                    'points' => $item->buy_from != 1 ? $item['point_price'] : null,
                    'prices' => $item->buy_from == 1 ? $item['point_price'] : null,
                    'video_url' => isset($item->video_url) ? ($item->video_url['path'] ?? null) : null,
                    'audio_url' => isset($item->audio_url) ? ($item->audio_url['path'] ?? null) : null,
                    'document_url' => isset($item->document_url) ? ($item->document_url['path'] ?? null) : null,
                ];

            });

            return Helpers::successResponse('HumanOp Shop Resource', $formatted);

        } catch (\Exception $e) {

             return Helpers::serverErrorResponse($e->getMessage());

        }

    }

    public function suggestedItems()
    {
        try {

            $getSuggestedItems = ShopCategoryResource::suggestedItems(Helpers::getUser()['id']);

            $formatted = $getSuggestedItems->map(function ($item) {

                return [
                    'id' => $item->id,
                    'category_name' => $item->shopCategory->name ?? null,
                    'heading' => $item->heading,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'buy_from' => $item->buy_from == 1 ? 'Price' : 'Point',
                    'points' => $item->buy_from != 1 ? $item['point_price'] : null,
                    'prices' => $item->buy_from == 1 ? $item['point_price'] : null,
                    'video_url' => isset($item->video_url) ? ($item->video_url['path'] ?? null) : null,
                    'audio_url' => isset($item->audio_url) ? ($item->audio_url['path'] ?? null) : null,
                    'document_url' => isset($item->document_url) ? ($item->document_url['path'] ?? null) : null,
                ];

            });

            return Helpers::successResponse('HumanOp Shop Suggested Items', $formatted);

        } catch (\Exception $e) {

            return Helpers::serverErrorResponse($e->getMessage());

        }

    }
    public function suggestedItemCheckout(SuggestionItemRequest $request)
    {
        try {

            $user = Helpers::getUser();

            $itemId = $request['item_id'];

            $buyFrom = $request['buy_from']; // 1 = money, 2 = points

            $itemAlreadyOwned = HumanOpLibraries::getItem($itemId, $user['id']);

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

                    HumanOpLibraries::addItem($user['id'], $itemId);

                    return Helpers::successResponse("You have successfully purchased the item.");

                } else {

                    return Helpers::validationResponse("Payment failed. Please try again.");

                }

            } else {

                $userPoints = HumanOpPoints::getUserPoints($user);

                if (($userPoints) && ($userPoints['points'] >= $request['points'])) {

                    HumanOpPoints::deductPoint($user['id'], $request['points']);

                    HumanOpLibraries::addItem($user['id'], $itemId);

                    return Helpers::successResponse("You have successfully redeemed the item using points.");

                } else {

                    return Helpers::validationResponse("Not enough points to redeem this item.");

                }

            }

        } catch (\Exception $e) {

            return Helpers::serverErrorResponse($e->getMessage());

        }

    }

}
