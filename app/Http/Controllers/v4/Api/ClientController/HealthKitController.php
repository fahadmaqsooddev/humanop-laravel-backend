<?php

namespace App\Http\Controllers\v4\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\v4\Api\Client\EnergyShield\IngestLocationsRequest;
use App\Http\Requests\v4\Api\Client\EnergyShield\IngestSamplesRequest;
use App\Jobs\v4\ProcessShieldEventsJob;
use App\Jobs\v4\RebuildDailyStateJob;
use App\Models\Assessment;
use App\Models\User;
use App\Models\v4\Client\BiometricSample;
use App\Models\v4\Client\LocationSample;
use App\Models\v4\Client\UserHumanOpProfile;
use Illuminate\Http\JsonResponse;

class HealthKitController extends Controller
{

    public function ingestSamples(IngestSamplesRequest $request): JsonResponse
    {

        $user = Helpers::getUser();
        $created = 0;

        foreach ($request->input('samples', []) as $sample) {
            $dedupeKey = hash('sha256', implode('|', [
                $user->id,
                $sample['metric'],
                $sample['recorded_at'],
                $sample['value'],
                $sample['source'] ?? 'apple_health',
            ]));

            $record = BiometricSample::query()->firstOrCreate(
                ['dedupe_key' => $dedupeKey],
                [
                    'user_id' => $user->id,
                    'metric' => $sample['metric'],
                    'value' => $sample['value'],
                    'recorded_at' => $sample['recorded_at'],
                    'source' => $sample['source'] ?? 'apple_health',
                    'metadata' => $sample['metadata'] ?? null,
                ]
            );

            if ($record->wasRecentlyCreated) {
                $created++;
            }
        }

        $assessment = Assessment::getLatestAssessment($user->id);

        $record = UserHumanOpProfile::getSingleRecord($user->id);

        if (!$assessment || empty($record) || $assessment->id != $record->assessment_id) {

            $this->createOrUpdate($user, $assessment, $request);

        }

        RebuildDailyStateJob::dispatch($user->id);

        ProcessShieldEventsJob::dispatch($user->id);

        return Helpers::successResponse('Samples ingested successfully', $created);

    }

    public function ingestLocations(IngestLocationsRequest $request): JsonResponse
    {

        $user = Helpers::getUser();
        $created = 0;

        foreach ($request->input('locations', []) as $location) {
            $dedupeKey = hash('sha256', implode('|', [
                $user->id,
                $location['place_id'],
                $location['recorded_at'],
            ]));

            $record = LocationSample::query()->firstOrCreate(
                ['dedupe_key' => $dedupeKey],
                [
                    'user_id' => $user->id,
                    'place_id' => $location['place_id'],
                    'latitude' => $location['latitude'] ?? null,
                    'longitude' => $location['longitude'] ?? null,
                    'recorded_at' => $location['recorded_at'],
                    'metadata' => $location['metadata'] ?? null,
                ]
            );

            if ($record->wasRecentlyCreated) {
                $created++;
            }
        }

        ProcessShieldEventsJob::dispatch($user->id);

        return Helpers::successResponse('Locations ingested successfully', $created);

    }

    public function createOrUpdate($user = null, $assessment = null, $request = null)
    {
        $topThreeStyles = $assessment != null ? Assessment::getAllStyles($assessment) : [];

        $topFeatures = $assessment != null ? Assessment::getFeatures($assessment) : [];

        $topTwoFeatures = !empty($topFeatures['top_two_keys']) ? Assessment::getTopTwoFeatures($topFeatures['top_two_keys'], $assessment) : [];

        $interval_of_life = $assessment != null ? User::getUserAge($user->date_of_birth, $assessment) : null;

        $energyPool = $assessment != null ? Assessment::getEnergyPoolPublicNamev4($assessment) : null;

        $energyPoolName = explode('energy_', $energyPool['name'])[1];

        UserHumanOpProfile::query()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'trait' => $topThreeStyles[0]['name'] ?? null,
                'pilot_driver' => $topTwoFeatures[0]['name'] ?? null,
                'copilot_driver' => $topTwoFeatures[1]['name'] ?? null,
                'interval' => $interval_of_life['name'],
                'energy_pool_state' => $energyPoolName,
                'preferences' => $request->input('preferences'),
                'assessment_id' => $assessment->id ?? null,
            ]
        );

    }

}
