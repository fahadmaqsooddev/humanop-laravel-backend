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

    public function resourceUrls()
    {
        try {

            $user = Helpers::getUser();

            $data = ResourceCategory::resourceCategoriesForClient($user['plan_name']);

//            $base_url = \request()->getSchemeAndHttpHost();
//
//            $data = [
//                'introduction_url' => $base_url . config('resource_library_urls.introduction_url'),
//                'traits_url' => $base_url . config('resource_library_urls.traits_url'),
//                'cycle_of_life_url' => $base_url . config('resource_library_urls.cycle_of_life_url'),
//                'motivational_drivers_url' => $base_url . config('resource_library_urls.motivational_drivers_url'),
//                'alchemic_boundries_url' => $base_url . config('resource_library_urls.alchemic_boundries_url'),
//                'communication_style_url' => $base_url . config('resource_library_urls.communication_style_url'),
//                'energy_pool_url' => $base_url . config('resource_library_urls.energy_pool_url'),
//                'perception_of_life_url' => $base_url . config('resource_library_urls.perception_of_life_url'),
//            ];

            return Helpers::successResponse('Library resources', $data);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }
}
