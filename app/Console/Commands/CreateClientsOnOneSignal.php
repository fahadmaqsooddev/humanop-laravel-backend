<?php

namespace App\Console\Commands;

use App\Jobs\v4\CreateOneSignalClientJob;
use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CreateClientsOnOneSignal extends Command
{

    protected $signature = 'onesignal:create-clients';

    protected $description = 'this command is used for registering the user in OneSignal';

    public function handle()
    {

        foreach (User::cursor() as $user) {

            try {

                CreateOneSignalClientJob::dispatch($user)->afterCommit();

            } catch (\Exception $e) {

                Log::error("OneSignal error for user {$user->id}: " . $e->getMessage());

            }

        }

    }

}
