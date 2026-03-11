<?php

namespace App\Http\Controllers\v4\Api\ClientController;

use App\Http\Controllers\Controller;
use App\Jobs\v4\RebuildDailyStateJob;
use App\Models\v4\Client\EnergyShieldState;
use App\Models\v4\Client\UserHumanOpProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class HumanOpProfileController extends Controller
{

    public function upsert(Request $request, EnergyShieldState $energyShieldService): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'trait' => ['nullable', 'string'],
            'pilot_driver' => ['nullable', 'string'],
            'copilot_driver' => ['nullable', 'string'],
            'interval' => ['nullable', 'string'],
            'energy_pool_state' => ['required', 'string', 'in:fair,average,excellent,above_excellent'],
            'preferences' => ['nullable', 'array'],
        ]);

        $validator->validate();

        $user = $request->user();

        $profile = UserHumanOpProfile::query()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'trait' => $request->input('trait'),
                'pilot_driver' => $request->input('pilot_driver'),
                'copilot_driver' => $request->input('copilot_driver'),
                'interval' => $request->input('interval'),
                'energy_pool_state' => $request->input('energy_pool_state'),
                'preferences' => $request->input('preferences'),
            ]
        );

        $energyShieldService->syncPoolWithoutReset($user->id, $profile->energy_pool_state);
        RebuildDailyStateJob::dispatch($user->id);

        return response()->json([
            'status' => 'ok',
            'profile' => $profile,
        ]);
    }

}
