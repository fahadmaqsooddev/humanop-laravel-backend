<?php

namespace App\Http\Controllers\v4\Api\ClientController\PlayList;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Admin\HumanOpItemsGridActivitiesLog;
use App\Models\Admin\Resources\LibraryResource;
use App\Models\Admin\Resources\ShopCategoryResource;
use App\Models\Assessment;
use App\Models\Libraries\HumanOpLibraries;
use App\Models\PlaylistLog;
use App\Models\Upload\Upload;
use Google\Service\Slides\Thumbnail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

            $alchemy = Assessment::getAlchemyDetail($userLatestAssessment);

            $communication = Assessment::getEnergy($userLatestAssessment);

            $topCommunication = collect(CodeDetail::getCommunicationDetail($communication, $userLatestAssessment))->pluck('public_name')->toArray();

            $topCommunication = [$topCommunication[0]];

            $perception = [Assessment::getPerceptionReportDetail($userLatestAssessment)['public_name']];

            $energyPool = Assessment::getEnergyPoolPublicName($userLatestAssessment)['public_name'];

            $energyPool = explode(' ', $energyPool);

            $energyPool = [$energyPool[0]];

            $userAssessmentGrid = array_merge($traits, $topTwoDrivers, !empty($alchemy['code_name']) ? [$alchemy['code_name']] : [], $topCommunication, $perception, $energyPool);

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
                                'document_url' => $item->document_url['path'] ?? null,
                                'allow_download' => $item->download_document === 1 ? true : false,
                                'resource_category_name' => optional($item->resourceCategory)->name,
                                'library_permission_name' => match (optional($item->libraryPermissions)->permission) {
                                    1 => 'Freemium',
                                    2 => 'Beta Breaker',
                                    3 => 'Premium',
                                    4 => 'Freemium Only',
                                    5 => 'Beta Breaker Only',
                                    6 => 'Premium Only',
                                    default => 'null',
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
                                'allow_download' => $item->download_document === 1 ? true : false,
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


    private function trackLists($type = 'audio')
    {
        $allLibraries = LibraryResource::allResourceCategories();
        $allShopResources = ShopCategoryResource::getResources();

        $resourceTransformed = [];
        $shopTransformed = [];

        //Batch fetch resource IDs

        //For First Loop

        $resourceIds = collect($allLibraries)->pluck('id');
        $playlists = PlaylistLog::whereIn('resource_item_id', $resourceIds)->get()->groupBy('resource_item_id');    
        $grids = HumanOpItemsGridActivitiesLog::whereIn('resource_item_id', $resourceIds)->get()->groupBy('resource_item_id');
        $paidItems = HumanOpLibraries::whereIn('library_resource_id', $resourceIds)->get()->keyBy('library_resource_id');


        //For Second Loop

        $shopIds = collect($allShopResources)->pluck('id');
        $shopPlaylists = PlaylistLog::whereIn('shop_item_id', $shopIds)->get()->groupBy('shop_item_id');
        $shopGrids = HumanOpItemsGridActivitiesLog::whereIn('shop_item_id', $shopIds)->get()->groupBy('shop_item_id');
        $paidShopItems = HumanOpLibraries::whereIn('item_id', $shopIds)->get()->keyBy('item_id');


        //For Grid Names (Combined Resources and Shops)

        $allGridNames = $grids->flatten()->pluck('grid_name')->merge(
            $shopGrids->flatten()->pluck('grid_name')
        )->unique()->filter();


        $codeDetails = CodeDetail::whereIn('code', $allGridNames)->get()->keyBy('code');


        //Work for Upload to avoid N+1 (Combined Resources and Shops)

        $allUploadIds = collect()
        ->merge($allLibraries->pluck('photo_id'))
        ->merge($allLibraries->pluck('thumbnail_id'))
        ->merge($allLibraries->pluck('upload_id'))
        ->merge($allLibraries->pluck('source_id'))
        ->merge($allLibraries->pluck('embed_link'))
        ->merge($allShopResources->pluck('image_id'))
        ->merge($allShopResources->pluck('thumbnail_id'))
        ->merge($allShopResources->pluck('upload_id'))
        ->merge($allShopResources->pluck('audio_id'))
        ->merge($allShopResources->pluck('video_id'))
        ->merge($allShopResources->pluck('document_id'))
        ->merge($allShopResources->pluck('video_embed_link'))
        ->filter()
        ->unique();


        // 3️⃣ Batch fetch all uploads

        $uploads = Upload::whereIn('id', $allUploadIds)->get()->keyBy('id');


        //(First Loop for Libraries Resource)

        foreach ($allLibraries as $item) {

            $playList = $playlists[$item->id] ?? collect();
            $gridsForItem = $grids[$item->id] ?? collect();
            $gridPublicName = $gridsForItem->map(fn($g) => $codeDetails->get($g['grid_name'])->public_name ?? null)
                               ->filter()
                               ->values()
                               ->toArray();

            $paid = $paidItems[$item->id] ?? null;

            $my_playlist = $playList->isNotEmpty() ? 1 : 0;

           if (empty($item->photo_url) && (!empty($item->video_url) || !empty($item->audio_url))) {

                $photoUrl = Helpers::getFileUrl($item->upload_id, $uploads);
                
                $thumbUrl = Helpers::getThumbnailUrl($item->thumbnail_id, $uploads);

                $document_urls = $item->documents
                    ->map(fn($doc) => [
                        'url' => $doc->document_url,
                        'downloadable' => (bool) $doc->download_document,
                    ])
                    ->values()
                ->all();

                $data = [
                    'id' => $item->id,
                    'heading' => $item->heading,
                    'my_playlist' => $my_playlist,
                    'slug' => $item->slug,
                    'description' => $item->description,
                    'content' => $item->content,
                    'relevance' => $item->relevance,
                    'photo_url' => Helpers::extractFilePath($photoUrl ?? 'path'),
                    'thumbnail_url' => Helpers::extractFilePath($thumbUrl ?? 'path'),
                    'document_urls' => $document_urls,
                    'resource_category_name' => optional($item->resourceCategory)->name,
                    'library_permission_name' => match (optional($item->libraryPermissions)->permission) {
                        1 => 'Freemium',
                        2 => 'Beta Breaker',
                        3 => 'Premium',
                        4 => 'Freemium Only',
                        5 => 'Beta Breaker Only',
                        6 => 'Premium Only',
                        default => 'null',
                    },
                    'price' => empty($paid) ? (int)(optional($item->libraryPermissions)->price ?? 0) : 0,
                    'point' => empty($paid) ? (int)(optional($item->libraryPermissions)->point ?? 0) : 0,
                    'grid' => $gridPublicName,
                ];


                if ($type === 'audio') {
                    $audioUrl = Helpers::getAudioUrl($item->upload_id, $uploads);
                    $newAudioURL = Helpers::extractFilePath($audioUrl ?? 'path');
                    $data['audio_url'] = $newAudioURL;
                } else {

                    $videoUrl = null;

                    if (!empty($item->source_id)) {
                        $videoUrl = Helpers::getGumletPlaybackUrl($item->source_id); // cached function
                    }

                    $newVideoURL=Helpers::getVideoUrl(
                        $item->upload_id,
                        $uploads,
                        $videoUrl,
                        $item->embed_link
                    );

                    $data['video_url'] = Helpers::extractFilePath($newVideoURL ?? 'path');

                }

                $resourceTransformed[] = $data;
            }
        }

        //(Second Loop for Shop Resources)

        foreach ($allShopResources as $resource) {

            $playList = $shopPlaylists[$resource->id] ?? collect();
            $gridsForResource = $shopGrids[$resource->id] ?? collect();
            $gridPublicName = $gridsForResource->map(fn($g) => $codeDetails->get($g['grid_name'])->public_name ?? null)
                                   ->filter()
                                   ->values()
                                   ->toArray();
            $paid = $paidShopItems[$resource->id] ?? null;
            $my_playlist = $playList->isNotEmpty() ? 1 : 0;


            

           if (empty($resource->document_url) && empty($resource->image_url) && (!empty($resource->video_url) || !empty($resource->audio_url))){


                $thumbUrl = Helpers::getThumbnailUrl($resource->thumbnail_id, $uploads);
                $documentUrl = Helpers::getDocumentUrl($resource->document_id, $uploads);
               
                $data = [
                    'id' => $resource->id,
                    'category_name' => optional($resource->shopCategory)->name,
                    'my_playlist' => $my_playlist,
                    'heading' => $resource->heading,
                    'created_at' => $resource->created_at,
                    'updated_at' => $resource->updated_at,
                    'point' => empty($paid) ? (int)($resource->point ?? 0) : 0,
                    'price' => empty($paid) ? (int)($resource->price ?? 0) : 0,
                    'document_url' => $documentUrl,
                    'thumbnail_url' => Helpers::extractFilePath($thumbUrl ?? 'path'),
                    'allow_download' => $resource->download_document === 1,
                    'grid' => $gridPublicName,
                ];


                if ($type === 'audio') {
                    $audioUrl = Helpers::getAudioUrl($resource->audio_id, $uploads);
                    $newAudioURL = Helpers::extractFilePath($audioUrl ?? 'path');
                    $data['audio_url'] = $newAudioURL;
                } else {

                    $videoUrl = Helpers::getVideoUrl($resource->upload_id, $uploads,null,$resource->video_embed_link);
                    $data['video_url'] =  Helpers::extractFilePath($videoUrl ?? 'path');
                }

                $shopTransformed[] = $data;
            }
        }

        return [
            'resource_items' => $resourceTransformed,
            'shop_items' => $shopTransformed,
        ];
    }

    public function soundTrackLists()
    {
        try {
            return Helpers::successResponse(
                "Sound Track Lists",
                $this->trackLists('audio')
            );
        } catch (\Exception $e) {
            return Helpers::serverErrorResponse($e->getMessage());
        }
    }

    public function videoTrackLists()
    {
        try {
            return Helpers::successResponse(
                "Video Track Lists",
                $this->trackLists('video')
            );
        } catch (\Exception $e) {
            return Helpers::serverErrorResponse($e->getMessage());
        }
    }

}
