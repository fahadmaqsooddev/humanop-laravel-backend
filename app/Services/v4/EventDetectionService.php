<?php

namespace App\Services\v4;
use App\Services\v4\EventDetection\DeprivationDetector;
use App\Services\v4\EventDetection\GluttonyDetector;
use App\Services\v4\EventDetection\ImmaturityDetector;
use App\Services\v4\EventDetection\IntimidationDetector;
use App\Services\v4\EventDetection\ManicDetector;
use App\Services\v4\EventDetection\NeglectDetector;
use App\Services\v4\EventDetection\PanicDetector;
use App\Services\v4\EventDetection\RigidityDetector;
use App\Services\v4\EventDetection\SelfAbsorptionDetector;
use App\Services\v4\EventDetection\StubbornnessDetector;
use App\Services\v4\EventDetection\VolatilityDetector;
use App\Services\v4\EventDetection\WoeIsMeDetector;

class EventDetectionService
{

    public function run(int $userId): void
    {
        $detectors = [
            PanicDetector::class,
            VolatilityDetector::class,
            StubbornnessDetector::class,
            GluttonyDetector::class,
            ManicDetector::class,
            ImmaturityDetector::class,
            NeglectDetector::class,
            IntimidationDetector::class,
            WoeIsMeDetector::class,
            DeprivationDetector::class,
            SelfAbsorptionDetector::class,
            RigidityDetector::class,
        ];

        foreach ($detectors as $detectorClass) {
            app($detectorClass)->detect($userId);
        }
    }

}
