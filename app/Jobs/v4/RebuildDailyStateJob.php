<?php

namespace App\Jobs\v4;

use App\Models\v4\Client\EnergyShieldState;
use App\Models\v4\Client\UserHumanOpProfile;
use App\Services\v4\DailyMetricsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RebuildDailyStateJob implements ShouldQueue
{

    use Dispatchable, Queueable;

    public function __construct(public int $userId)
    {
    }

    public function handle(DailyMetricsService $dailyMetricsService, EnergyShieldState $energyShieldService): void
    {
        $dailyMetricsService->rebuildForDate($this->userId, now());

        $profile = UserHumanOpProfile::query()->where('user_id', $this->userId)->first();
        $pool = $profile?->energy_pool_state ?? 'average';

        $energyShieldService->syncPoolWithoutReset($this->userId, $pool);
    }

}
