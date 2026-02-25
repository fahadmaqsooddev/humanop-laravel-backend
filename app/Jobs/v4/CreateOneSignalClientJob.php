<?php

namespace App\Jobs\v4;

use App\Services\v4\OneSignalServices\OneSignalService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateOneSignalClientJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $userId;
    public string $email;

    public $tries = 3;

    public $backoff = 10;

    public function __construct($user)
    {
        $this->userId = $user->id;
        $this->email  = $user->email;
    }

    public function handle(): void
    {

        OneSignalService::createClient($this->userId, $this->email);

    }
}
