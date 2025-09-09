<?php

namespace App\Http\Controllers\Api\ClientController\PlayList;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Admin\HumanOpItemsGridActivitiesLog;
use App\Models\Admin\Resources\LibraryResource;
use App\Models\Admin\Resources\ShopCategoryResource;
use App\Models\Assessment;
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

            $userLatestAssessment = Assessment::getLatestAssessment(Helpers::getUser()['id']);

            $traits = collect(Assessment::authenticTraits($userLatestAssessment))->pluck('public_name')->toArray();

            $getDrivers = Assessment::getFeatures($userLatestAssessment);

            $topTwoDrivers = collect(Assessment::getTopTwoFeatures($getDrivers['top_two_keys'], $userLatestAssessment))->pluck('public_name')->toArray();

            $alchemy = [Assessment::getAlchemyDetail($userLatestAssessment)['public_name']];

            $communication = Assessment::getEnergy($userLatestAssessment);

            $topCommunication = collect(CodeDetail::getCommunicationDetail($communication, $userLatestAssessment))->pluck('public_name')->toArray();

            $topCommunication = [$topCommunication[0]];

            $perception = [Assessment::getPreceptionReportDetail($userLatestAssessment)['public_name']];

            $energyPool = Assessment::getEnergyPoolPublicName($userLatestAssessment)['public_name'];

            $energyPool = explode(' ', $energyPool);

            $energyPool = [$energyPool[0]];

            $userAssessmentGrid = array_merge($traits, $topTwoDrivers, $alchemy, $topCommunication, $perception, $energyPool);

            $resourceTransformed = [];

            $shopTransformed = [];

            $searchGrids = $request->has('search_grid') ? (is_array($request->search_grid) ? $request->search_grid : [$request->search_grid]) : [];

            $searchName = $request->get('search_name');

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

                $matchAssessment = !empty(array_intersect($userAssessmentGrid, $gridPublicName));

                if ($matchAssessment) {

                    $matchName = empty($searchName) || strcasecmp($item['heading'], $searchName) === 0;

                    $matchGrid = empty($searchGrids) || !empty(array_intersect($searchGrids, $gridPublicName));

                    if ($matchName && $matchGrid) {

                        if ((empty($item->photo_url)) && (!empty($item->video_url) || !empty($item->audio_url))) {

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
                                'thumbnail_url' => $item->thumbnail_url['url'] ?? null,
                                'resource_category_name' => optional($item->resourceCategory)->name,
                                'library_permission_name' => match (optional($item->libraryPermissions)->permission) {
                                    1 => 'Freemium',
                                    2 => 'Core',
                                    3 => 'Premium',
                                    4 => 'HP Look',
                                    default => null,
                                },
                                'price' => empty($paid) ? (int)optional($item->libraryPermissions)->price ?? 0 : 0,
                                'point' => empty($paid) ? (int)optional($item->libraryPermissions)->point ?? 0 : 0,
                                'grid' => $gridPublicName,
                            ];

                        }

                    }

                }

            }

            // --- Shop Resources ---
            foreach ($allShopResources as $resource) {

                $playList = PlaylistLog::getSingleShopItem($resource['id']);

                $grids = HumanOpItemsGridActivitiesLog::getShopGrid($resource['id']);

                $gridPublicName = $getGridPublicNames($grids);

                $paid = HumanOpLibraries::singleShopBuyItems($resource['id']);

                $matchAssessment = !empty(array_intersect($userAssessmentGrid, $gridPublicName));

                if ($matchAssessment) {

                    $matchName = empty($searchName) || strcasecmp($resource['heading'], $searchName) === 0;

                    $matchGrid = empty($searchGrids) || !empty(array_intersect($searchGrids, $gridPublicName));

                    if ($matchName && $matchGrid) {

                        if ((empty($resource->document_url)) && (empty($resource->image_url)) && (!empty($resource->video_url) || !empty($resource->audio_url))) {

                            $shopTransformed[] = [
                                'id' => $resource->id,
                                'category_name' => $resource['shopCategory']['name'] ?? null,
                                'my_playlist' => !empty($playList) ? 1 : 0,
                                'heading' => $resource->heading,
                                'created_at' => $resource->created_at,
                                'updated_at' => $resource->updated_at,
                                'points' => empty($paid) ? (int)($resource->point ?? 0) : 0,
                                'prices' => empty($paid) ? (int)($resource->price ?? 0) : 0,
                                'video_url' => $resource->video_url['path'] ?? null,
                                'audio_url' => $resource->audio_url['path'] ?? null,
                                'document_url' => $resource->document_url['path'] ?? null,
                                'thumbnail_url' => $resource->thumbnail_url['url'] ?? null,
                                'grid' => $gridPublicName,
                            ];

                        }

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

                if ((empty($item->photo_url)) && (!empty($item->video_url) || !empty($item->audio_url))) {

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
                        'thumbnail_url' => $item->thumbnail_url['url'] ?? null,
                        'resource_category_name' => optional($item->resourceCategory)->name,
                        'library_permission_name' => match (optional($item->libraryPermissions)->permission) {
                            1 => 'Freemium',
                            2 => 'Core',
                            3 => 'Premium',
                            4 => 'HP Look',
                            default => null,
                        },
                        'price' => empty($paid) ? (int)(optional($item->libraryPermissions)->price ?? 0) : 0,
                        'point' => empty($paid) ? (int)(optional($item->libraryPermissions)->point ?? 0) : 0,
                        'grid' => $gridPublicName,
                    ];

                }

            }

            foreach ($allShopResources as $resource) {

                $playList = PlaylistLog::getSingleShopItem($resource['id']);

                $grids = HumanOpItemsGridActivitiesLog::getShopGrid($resource['id']);

                $gridPublicName = $getGridPublicNames($grids);

                $paid = HumanOpLibraries::singleShopBuyItems($resource['id']);

                if (empty($resource->document_url) && empty($resource->image_url) && (!empty($resource->video_url) || !empty($resource->audio_url))) {

                    $shopTransformed[] = [
                        'id' => $resource->id,
                        'category_name' => $resource['shopCategory']['name'] ?? null,
                        'my_playlist' => !empty($playList) ? 1 : 0,
                        'heading' => $resource->heading,
                        'created_at' => $resource->created_at,
                        'updated_at' => $resource->updated_at,
                        'points' => empty($paid) ? (int)($resource->point ?? 0) : 0,
                        'prices' => empty($paid) ? (int)($resource->price ?? 0) : 0,
                        'video_url' => $resource->video_url['path'] ?? null,
                        'audio_url' => $resource->audio_url['path'] ?? null,
                        'document_url' => $resource->document_url['path'] ?? null,
                        'thumbnail_url' => $resource->thumbnail_url['url'] ?? null,
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
