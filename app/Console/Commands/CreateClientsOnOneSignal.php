<?php

namespace App\Console\Commands;

use App\Services\v4\OneSignalServices\OneSignalService;
use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CreateClientsOnOneSignal extends Command
{

    protected $signature = 'onesignal:create-clients';

    protected $description = 'this command is used for registering the user in OneSignal';

    public function __construct(private OneSignalService $oneSignal) {}

    public function handle()
    {

        foreach (User::cursor() as $user) {

            try {

                $this->oneSignal->createClient($user->id, $user->email);

            } catch (\Exception $e) {

                Log::error("OneSignal error for user {$user->id}: " . $e->getMessage());

            }

        }

    }

}
