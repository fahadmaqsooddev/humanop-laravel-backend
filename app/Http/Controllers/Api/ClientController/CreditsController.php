<?php

namespace App\Http\Controllers\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Client\Point\Point;
use Illuminate\Http\Request;

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
