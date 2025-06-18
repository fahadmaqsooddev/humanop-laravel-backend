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

            $data = ResourceCategory::resourceCategoriesForClient($request['type'], $request['access'], $request['relevance']);

            return Helpers::successResponse('Library resources', $data);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }
}
