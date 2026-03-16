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
    protected array $detectors = [

        PanicDetector::class,
        VolatilityDetector::class,
        IntimidationDetector::class,
        StubbornnessDetector::class,
        GluttonyDetector::class,
        ManicDetector::class,
        ImmaturityDetector::class,
        NeglectDetector::class,
        WoeIsMeDetector::class,
        DeprivationDetector::class,
        SelfAbsorptionDetector::class,
        RigidityDetector::class

    ];

    public function run(int $userId)
    {
        foreach ($this->detectors as $detector) {

            $result = app($detector)->detect($userId);

            if ($result) {
                return $result;
            }

        }
    }
}
