<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Admin\ResourceCategory\ResourceCategory;
use App\Models\Admin\Resources\LibraryResource;
use Illuminate\Http\Request;

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
                $transformed[] = [
                    'id' => $item->id,
                    'heading' => $item->heading,
                    'slug' => $item->slug,
                    'description' => $item->description,
                    'content' => $item->content,
                    'relevance' => $item->relevance,
                    'photo_url' => $item->photo_url ?? null,
                    'video_url' => $item->video_url ?? null,
                    'audio_url' => $item->audio_url ?? null,
                    'resource_category_name' => optional($item->resourceCategory)->name,
                    'library_permission_name' => match(optional($item->libraryPermissions)->permission) {
                        1 => 'Freemium',
                        2 => 'Core',
                        3 => 'Premium',
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
}
