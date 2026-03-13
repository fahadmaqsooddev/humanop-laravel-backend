<?php

namespace App\Http\Controllers\v4\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Services\v4\EnergyShieldService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class EnergyShieldController extends Controller
{

    public function show(Request $request, EnergyShieldService $energyShieldService): JsonResponse
    {
        $state = $energyShieldService->getState(Helpers::getUser()->id);

        return Helpers::successResponse('Energy shield state', $state);

    }

}
