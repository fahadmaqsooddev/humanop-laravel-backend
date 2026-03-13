<?php

namespace App\Jobs\v4;

use App\Services\v4\EventDetectionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessShieldEventsJob implements ShouldQueue
{

    use Dispatchable, Queueable;

    public function __construct(public int $userId)
    {
    }

    public function handle(EventDetectionService $eventDetectionService): void
    {
        $eventDetectionService->run($this->userId);
    }

}
