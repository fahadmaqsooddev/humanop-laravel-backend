<?php

namespace App\Http\Controllers\v4\Api\ClientController;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\v4\Api\Client\EnergyShield\IngestLocationsRequest;
use App\Http\Requests\v4\Api\Client\EnergyShield\IngestSamplesRequest;
use App\Jobs\v4\ProcessShieldEventsJob;
use App\Jobs\v4\RebuildDailyStateJob;
use App\Models\v4\Client\BiometricSample;
use App\Models\v4\Client\LocationSample;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class HealthKitController extends Controller
{

    public function ingestSamples(IngestSamplesRequest $request): JsonResponse
    {

        $user = $request->user();
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

        RebuildDailyStateJob::dispatch($user->id);
        ProcessShieldEventsJob::dispatch($user->id);

        return Helpers::successResponse('Samples ingested successfully', $created);

    }

    public function ingestLocations(IngestLocationsRequest $request): JsonResponse
    {

        $user = $request->user();
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

}
