<?php

namespace App\Http\Controllers\v4\Api\ClientController;

use App\Http\Controllers\Controller;
use App\Jobs\v4\RebuildDailyStateJob;
use App\Models\Assessment;
use App\Models\User;
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
//            'trait' => ['nullable', 'string'],
//            'pilot_driver' => ['nullable', 'string'],
//            'copilot_driver' => ['nullable', 'string'],
//            'interval' => ['nullable', 'string'],
//            'energy_pool_state' => ['required', 'string', 'in:fair,average,excellent,above_excellent'],
            'preferences' => ['nullable', 'array'],
        ]);

        $validator->validate();

        $user = $request->user();

        $assessment = Assessment::getLatestAssessment($user->id);

        $topThreeStyles = $assessment != null ? Assessment::getAllStyles($assessment) : [];

        $topFeatures = $assessment != null ? Assessment::getFeatures($assessment) : [];

        $topTwoFeatures = !empty($topFeatures['top_two_keys']) ? Assessment::getTopTwoFeatures($topFeatures['top_two_keys'], $assessment) : [];

        $interval_of_life = $assessment != null ? User::getUserAge($user->date_of_birth, $assessment) : null;

        $energyPool = $assessment != null ? Assessment::getEnergyPoolPublicNamev4($assessment) : null;

        $profile = UserHumanOpProfile::query()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'trait' => $topThreeStyles[0]['public_name'],
                'pilot_driver' => $topTwoFeatures[0]['public_name'],
                'copilot_driver' => $topTwoFeatures[1]['public_name'],
                'interval' => $interval_of_life['name'],
                'energy_pool_state' => $energyPool['public_name'],
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
