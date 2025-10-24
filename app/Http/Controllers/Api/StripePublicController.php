<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Support\StripeConfig;
use Illuminate\Http\JsonResponse;

class StripePublicController extends Controller
{

    public function publishable(): JsonResponse
    {
        return response()->json([
            'publishable_key' => StripeConfig::publishableKey(),
        ]);
    }

}
