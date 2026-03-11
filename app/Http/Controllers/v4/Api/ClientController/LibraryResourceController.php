<?php

namespace App\Http\Controllers\v4\Api\ClientController;

use App\Enums\Admin\Admin;
use App\Helpers\ActivityLogs\ActivityLogger;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\LibraryResourceSuggestionItemRequest;
use App\Models\Admin\MediaPlayer\MediaPlayerCategories;
use App\Models\Admin\MediaPlayer\MediaPlayerResources;
use App\Models\Admin\ResourceCategory\ResourceCategory;
use App\Models\Admin\Resources\LibraryResource;
use App\Models\v4\Client\HumanOpPoints\HumanOpPoints;
use App\Models\v4\Client\PurchasedItems;
use App\Models\Libraries\HumanOpLibraries;
use App\Models\PlaylistLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Charge;
use Stripe\Stripe;
use App\Http\Requests\v4\Api\Client\LibraryResourceNotesRequest;
use App\Models\v4\Client\LibraryResourceNotes\LibraryResourceNotes;
class LibraryResourceController extends Controller
{

    public $user=null;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user=Helpers::getUser();
    }

    public function resourceUrls(Request $request)
    {
        try {

            $query = LibraryResource::resourceCategoriesForClient(
                $request->input('type'),
                $request->input('access'),
                $request->input('relevance'),
                $request->input('search_name')
            );

            $data = Helpers::pagination(
                $query,
                $request->input('pagination', true),
                $request->input('per_page')
            );

            $items = $request->input('pagination', true) ? $data->items() : $data;

            $transformed = [];

            $user = Helpers::getUser();

            $userPlan = $user['plan_name'];

            foreach ($items as $item) {

                $playList = PlaylistLog::getSingleResourceItem($item->id);

                $libraryPermission = optional($item->libraryPermissions);

                $permission = $libraryPermission->permission ?? null;

                $basePrice = (int) ($libraryPermission->price ?? 0);

                $finalPrice = ($userPlan === Admin::PREMIUM_PLAN_NAME && $basePrice) ? $basePrice * 0.50 : $basePrice;

                $libraryPermissionName = match ($permission) {
                    1 => 'Freemium',
                    2 => 'Beta Breaker',
                    3 => 'Premium',
                    4 => 'Freemium Only',
                    5 => 'Beta Breaker Only',
                    6 => 'Premium Only',
                    default => null,
                };

                $libraryPermissionAllow = match ($permission) {

                    // Freemium resource
                    1 => true,

                    // Freemium only
                    4 => $userPlan === 'Freemium',

                    // Beta breaker
                    2 => in_array($userPlan, ['Beta Breaker', 'Premium']),

                    // Beta breaker only
                    5 => $userPlan === 'Beta Breaker',

                    // Premium
                    3 => true,

                    // Premium only
                    6 => $userPlan === 'Premium',

                    default => false,
                };

                $transformed[] = [
                    'id' => $item->id,
                    'heading' => $item->heading,
                    'my_playlist' => !empty($playList) ? 1 : 0,
                    'slug' => $item->slug,
                    'description' => $item->description,
                    'content' => $item->content,
                    'relevance' => $item->relevance,
                    'photo_url' => $item->photo_url ?: null,
                    'video_url' => $item->video_url ?: null,
                    'audio_url' => $item->audio_url ?: null,
                    'thumbnail_url' => $item->thumbnail_url['url'] ?? null,
                    'document_url' => $item->document_url['path'] ?? null,
                    'allow_download' => $item->download_document == 1,
                    'resource_category_name' => optional($item->resourceCategory)->name,
                    'library_permission_name' => $libraryPermissionName,
                    'library_permission_allow' => $libraryPermissionAllow,
                    'price' => $finalPrice,
                    'point' => (int) ($libraryPermission->point ?? 0),
                ];
            }

            if ($request->input('pagination', true)) {

                $data->setCollection(collect($transformed));

                return Helpers::successResponse('Library resources', $data, true);

            }

            return Helpers::successResponse('Library resources', $transformed);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    
    public function getResourceUrl(Request $request)
    {
        try {

            $validated = $request->validate([
                'resource_id' => 'required|exists:library_resources,id',
            ]);

            $resource = LibraryResource::getResourceById($validated['resource_id']);

            if (!$resource) {
                return Helpers::notFoundResponse('Resource not found');
            }

            return Helpers::successResponse('Resource URL Fetch', $resource);

        } catch (\Exception $e) {
            return Helpers::serverErrorResponse('Something went wrong. Please contact technical support');
        }
    }

    public function mediaPlayerCategories()
    {
        try {
            $categories = MediaPlayerCategories::dropDownCategories();

            return Helpers::successResponse('Media Player Categories', $categories);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function mediaPlayerResources(Request $request)
    {
        try {

            $resources = MediaPlayerResources::getMediaPlayResources($request['media_player_id']);

            $mediaResources = [];

            foreach ($resources as $resource) {
                $mediaResources[] = [
                    'id' => $resource->id,
                    'heading' => $resource->heading,
                    'category_name' => $resource->resourceCategory->name,
                    'description' => $resource->description,
                    'audio_url' => $resource->audio_url ?? null,
                    'video_url' => $resource->video_url ?? null,
                    'thumbnail_url' => $resource->thumbnail_url ? $resource->thumbnail_url['url'] : null,
                ];

            }
            return Helpers::successResponse('Media Player Resource List', $mediaResources);

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

            DB::beginTransaction();

            $user = Helpers::getUser();

            $itemId = $request['item_id'];

            $resourceName = LibraryResource::singleLibraryResource($itemId)['heading'];

            $buyFrom = $request['buy_from']; // 1 = money, 2 = points

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

                    $name = "You have purchased Tool & Training item {$resourceName}";

                    PurchasedItems::createItem($user['id'], $name, $request['price'], Admin::B2C_PURCHASED_ITEM);

                    ActivityLogger::addLog('Tool & Training Purchased', "You have purchased the tool & training item '{$resourceName}' for \${$request['price']}.");

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

                    ActivityLogger::addLog('Tool & Training Purchased', "You have purchased the tool & training item '{$resourceName}' for {$request['points']} Humanop Points.");

                    DB::commit();

                    return Helpers::successResponse("You have successfully redeemed the item using points.");

                } else {

                    return Helpers::validationResponse("Not enough points to redeem this item.");

                }

            }

        } catch (\Exception $e) {

            DB::rollback();

            return Helpers::serverErrorResponse($e->getMessage());

        }

    }

    public function addLibraryResourceNotes(LibraryResourceNotesRequest $request)
    {
        $user_id = $this->user->id;

        $notes = LibraryResourceNotes::createLibraryResourceNote(
            $request->validated(),
            $user_id
        );

        return Helpers::successResponse('Library Resource Notes saved successfully', $notes);
    }


    public function getLibraryResourceNotes(Request $request)
    {

        $request->validate([
            'resource_id' => 'required|integer'
        ]);

        $user_id = $this->user->id;
        $resource_id = $request->query('resource_id');

        $note = LibraryResourceNotes::getLibraryResourceNote($resource_id, $user_id);

        if (!$note) {
            return Helpers::notFoundResponse('Library Resource Note not found');
        }

        return Helpers::successResponse('Library Resource Note fetched successfully', $note);
    }

}
