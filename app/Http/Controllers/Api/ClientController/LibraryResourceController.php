<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\LibraryResourceSuggestionItemRequest;
use App\Http\Requests\Api\Client\SuggestionItemRequest;
use App\Models\Admin\ResourceCategory\ResourceCategory;
use App\Models\Admin\Resources\LibraryResource;
use App\Models\Client\HumanOpPoints\HumanOpPoints;
use App\Models\Libraries\HumanOpLibraries;
use App\Models\PlaylistLog;
use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Stripe;

class LibraryResourceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function resourceUrls(Request $request)
    {
        try {
            $data = LibraryResource::resourceCategoriesForClient($request['type'], $request['access'], $request['relevance']);

            $transformed = [];

            foreach ($data as $item) {

                $playList = PlaylistLog::getSingleResourceItem($item['id']);

                $transformed[] = [
                    'id' => $item->id,
                    'heading' => $item->heading,
                    'my_playlist' => !empty($playList) ? 1 : 0,
                    'slug' => $item->slug,
                    'description' => $item->description,
                    'content' => $item->content,
                    'relevance' => $item->relevance,
                    'photo_url' => !empty($item->photo_url) ? $item->photo_url : null,
                    'video_url' => !empty($item->video_url) ? $item->video_url : null,
                    'audio_url' => !empty($item->audio_url) ? $item->audio_url : null,
                    'resource_category_name' => optional($item->resourceCategory)->name,
                    'library_permission_name' => match(optional($item->libraryPermissions)->permission) {
                        1 => 'Freemium',
                        2 => 'Core',
                        3 => 'Premium',
                        4 => 'HP Look', // or whatever label you want for permission 4
                        default => 'null',
                    },
                    'price' => optional($item->libraryPermissions)->price,
                    'point' => optional($item->libraryPermissions)->point,
                ];
            }

            return Helpers::successResponse('Library resources', $transformed);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function resourceCategories()
    {
        try {

            $resourceCategories = ResourceCategory::resourceCategories();

            return Helpers::successResponse('Library resources categories', $resourceCategories);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }


    public function libraryResourceItemCheckout(LibraryResourceSuggestionItemRequest $request)
    {
        try {

            $user = Helpers::getUser();

            $itemId = $request['item_id'];

            $buyFrom = $request['buy_from']; // 1 = money, 2 = points
//            type 2 mean libraray resource
            $type=2;

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

                    return Helpers::successResponse("You have successfully purchased the item.");

                } else {

                    return Helpers::validationResponse("Payment failed. Please try again.");

                }

            } else {

                $userPoints = HumanOpPoints::getUserPoints($user);


                if (($userPoints) && ($userPoints['points'] >= $request['points'])) {

                    HumanOpPoints::deductPoint($user['id'], $request['points']);

                    HumanOpLibraries::addItem($user['id'], $itemId,$type);

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
