<?php

namespace App\Console\Commands;

use App\Services\v4\OneSignalServices\OneSignalService;
use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class createClientsOnOneSignal extends Command
{

    protected $signature = 'create:createClientsOnOneSignal';

    protected $description = 'this command is used for registering the user in OneSignal';

    public function handle()
    {

        foreach (User::all()->cursor() as $user) {

            try {

                OneSignalService::createClient($user->id);

            } catch (\Exception $e) {

                Log::error("OneSignal error for user {$user->id}: " . $e->getMessage());

            }

        }

    }

}
