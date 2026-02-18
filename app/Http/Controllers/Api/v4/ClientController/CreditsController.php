<?php

namespace App\Http\Controllers\Api\v4\ClientController;

use App\Helpers\v4\Helpers;
use App\Http\Controllers\Controller;
use App\Models\v4\Client\Point\Point;

class CreditsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getCreditDetails(){

        try {

            $data = Point::getPoints();

            return Helpers::successResponse("Credit details", $data);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }
}
