<?php

namespace App\Services\v4\EventDetection;

interface EventDetectorInterface
{

    public function detect(int $userId): bool;

}
