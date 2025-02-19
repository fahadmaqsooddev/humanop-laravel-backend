<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Helpers\Helpers;

class createClientsOnOneSignal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:createClientsOnOneSignal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command is used for registering the user in OneSignal';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = User::allUser();

        foreach ($data as $user) {

            Helpers::createClientsOnOneSignal($user['id']);

        }

    }
}
