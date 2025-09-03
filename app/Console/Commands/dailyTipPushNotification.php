<?php

namespace App\Console\Commands;

use App\Jobs\SendDailyTip;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class dailyTipPushNotification extends Command
{

    protected $signature = 'tips:dispatch-due';

    protected $description = 'Dispatch daily tips for schedules that are due';

    public function handle()
    {


        $lock = Cache::lock('tips:dispatch-due', 55);

        if (!$lock->get()) {

            $this->info('Another dispatcher is running. Exiting.');

            return 0;

        }

        try {

            User::query()
                ->where('is_admin', 2)
                ->chunkById(1000, function ($users) {

                    foreach ($users as $user) {

                        Log::info('command start');

                        dispatch(new SendDailyTip($user['id']))
                            ->onQueue('tips');

                        Log::info('Queue end');

                    }

                });

        } finally {

            optional($lock)->release();

        }

        return 0;

    }

}
