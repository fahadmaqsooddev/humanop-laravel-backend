<?php

namespace App\Http\Controllers\Api\ClientController\PlayList;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Admin\HumanOpItemsGridActivitiesLog;
use App\Models\Admin\Resources\LibraryResource;
use App\Models\Admin\Resources\ShopCategoryResource;
use App\Models\Libraries\HumanOpLibraries;
use App\Models\PlaylistLog;
use Illuminate\Http\Request;

class SoundTrackController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');

    }

    public function recommendedSoundTrackLists(Request $request)
    {
        try {
            $allLibraries = LibraryResource::allResourceCategories();
            $allShopResources = ShopCategoryResource::getResources();

            $resourceTransformed = [];
            $shopTransformed = [];

            $searchNames = $request->has('search_name')
                ? (is_array($request->search_name) ? $request->search_name : [$request->search_name])
                : [];

            $getGridPublicNames = function ($grids) {
                $names = [];
                foreach ($grids as $grid) {
                    $public = CodeDetail::getSinglePublicName($grid['grid_name']);
                    if (!empty($public['public_name'])) {
                        $names[] = $public['public_name'];
                    }
                }
                return $names;
            };

            // --- Libraries ---
            foreach ($allLibraries as $item) {
                $playList = PlaylistLog::getSingleResourceItem($item['id']);
                $grids = HumanOpItemsGridActivitiesLog::getResourceGrid($item['id']);
                $paid = HumanOpLibraries::singleLibraryBuyItems($item['id']);
                $gridPublicName = $getGridPublicNames($grids);

                $match = [];
                if (!empty($searchNames)) {
                    $match = array_intersect($searchNames, $gridPublicName);
                }

                // agar search_name hai to sirf matched grids include karo
                if (empty($searchNames) || !empty($match)) {
                    if (empty($item->photo_url)) {
                        $resourceTransformed[] = [
                            'id' => $item->id,
                            'heading' => $item->heading,
                            'my_playlist' => !empty($playList) ? 1 : 0,
                            'slug' => $item->slug,
                            'description' => $item->description,
                            'content' => $item->content,
                            'relevance' => $item->relevance,
                            'photo_url' => $item->photo_url ?? null,
                            'video_url' => $item->video_url ?? null,
                            'audio_url' => $item->audio_url ?? null,
                            'resource_category_name' => optional($item->resourceCategory)->name,
                            'library_permission_name' => match (optional($item->libraryPermissions)->permission) {
                                1 => 'Freemium',
                                2 => 'Core',
                                3 => 'Premium',
                                4 => 'HP Look',
                                default => null,
                            },
                            'price' => empty($paid) ? optional($item->libraryPermissions)->price : null,
                            'point' => empty($paid) ? optional($item->libraryPermissions)->point : null,
                            'grid' => $gridPublicName,
                        ];
                    }
                }
            }

            // --- Shop Resources ---
            foreach ($allShopResources as $resource) {
                $playList = PlaylistLog::getSingleShopItem($resource['id']);
                $grids = HumanOpItemsGridActivitiesLog::getShopGrid($resource['id']);
                $gridPublicName = $getGridPublicNames($grids);
                $paid = HumanOpLibraries::singleLibraryBuyItems($resource['id']);

                $match = [];
                if (!empty($searchNames)) {
                    $match = array_intersect($searchNames, $gridPublicName);
                }

                if (empty($searchNames) || !empty($match)) {
                    if (empty($resource->document_url)) {
                        $shopTransformed[] = [
                            'id' => $resource->id,
                            'category_name' => $resource->name ?? null,
                            'my_playlist' => !empty($playList) ? 1 : 0,
                            'heading' => $resource->heading,
                            'created_at' => $resource->created_at,
                            'updated_at' => $resource->updated_at,
                            'points' => empty($paid) ? (int)($resource->point ?? 0) : null,
                            'prices' => empty($paid) ? (int)($resource->price ?? 0) : null,
                            'video_url' => $resource->video_url['path'] ?? null,
                            'audio_url' => $resource->audio_url['path'] ?? null,
                            'document_url' => $resource->document_url['path'] ?? null,
                            'grid' => $gridPublicName,
                        ];
                    }
                }
            }

            $transformed = [
                'resource_items' => $resourceTransformed,
                'shop_items' => $shopTransformed,
            ];

            return Helpers::successResponse("Sound Track Lists", $transformed);
        } catch (\Exception $exception) {
            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function soundTrackLists()
    {
        try {

            $allLibraries = LibraryResource::allResourceCategories();

            $allShopResources = ShopCategoryResource::getResources();

            $resourceTransformed = [];

            $shopTransformed = [];

            $getGridPublicNames = function ($grids) {

                $names = [];

                foreach ($grids as $grid) {

                    $public = CodeDetail::getSinglePublicName($grid['grid_name']);

                    if (!empty($public['public_name'])) {

                        $names[] = $public['public_name'];

                    }

                }

                return $names;

            };

            foreach ($allLibraries as $item) {

                $playList = PlaylistLog::getSingleResourceItem($item['id']);

                $grids = HumanOpItemsGridActivitiesLog::getResourceGrid($item['id']);

                $gridPublicName = $getGridPublicNames($grids);

                $paid = HumanOpLibraries::singleLibraryBuyItems($item['id']);

                if (empty($item->photo_url)) {
                    $resourceTransformed[] = [
                        'id' => $item->id,
                        'heading' => $item->heading,
                        'my_playlist' => !empty($playList) ? 1 : 0,
                        'slug' => $item->slug,
                        'description' => $item->description,
                        'content' => $item->content,
                        'relevance' => $item->relevance,
                        'photo_url' => $item->photo_url ?? null,
                        'video_url' => $item->video_url ?? null,
                        'audio_url' => $item->audio_url ?? null,
                        'resource_category_name' => optional($item->resourceCategory)->name,
                        'library_permission_name' => match (optional($item->libraryPermissions)->permission) {
                            1 => 'Freemium',
                            2 => 'Core',
                            3 => 'Premium',
                            4 => 'HP Look',
                            default => null,
                        },
                        'price' => empty($paid) ? optional($item->libraryPermissions)->price : null,
                        'point' => empty($paid) ? optional($item->libraryPermissions)->point : null,
                        'grid' => $gridPublicName,
                    ];
                }
            }

            foreach ($allShopResources as $resource) {

                $playList = PlaylistLog::getSingleShopItem($resource['id']);

                $grids = HumanOpItemsGridActivitiesLog::getShopGrid($resource['id']);

                $gridPublicName = $getGridPublicNames($grids);

                $paid = HumanOpLibraries::singleLibraryBuyItems($resource['id']);

                if (empty($resource->document_url)) {

                    $shopTransformed[] = [
                        'id' => $resource->id,
                        'category_name' => $resource->name ?? null,
                        'my_playlist' => !empty($playList) ? 1 : 0,
                        'heading' => $resource->heading,
                        'created_at' => $resource->created_at,
                        'updated_at' => $resource->updated_at,
                        'points' => (int)($resource->point ?? 0),
                        'prices' => (int)($resource->price ?? 0),
                        'video_url' => $resource->video_url['path'] ?? null,
                        'audio_url' => $resource->audio_url['path'] ?? null,
                        'document_url' => $resource->document_url['path'] ?? null,
                        'grid' => $gridPublicName,
                    ];

                }

            }

            $transformed = [
                'resource_items' => $resourceTransformed,
                'shop_items' => $shopTransformed,
            ];

            return Helpers::successResponse("Sound Track Lists", $transformed);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

}
