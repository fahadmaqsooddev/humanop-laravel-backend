<?php

namespace App\Http\Controllers\v4\Api\ClientController;

use App\Http\Controllers\Controller;
use App\Models\v4\Client\EnergyShieldState;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class EnergyShieldController extends Controller
{

    public function show(Request $request, EnergyShieldState $energyShieldService): JsonResponse
    {
        $state = $energyShieldService->getState($request->user()->id);

        return response()->json([
            'status' => 'ok',
            'state' => $state,
        ]);
    }

}
