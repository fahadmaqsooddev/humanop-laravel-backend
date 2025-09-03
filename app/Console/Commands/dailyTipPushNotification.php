<?php

namespace App\Console\Commands;

use App\Enums\Admin\Admin;
use App\Events\DailyTip\NewDailyTip;
use App\Helpers\HaiChat\HaiChatHelpers;
use App\Helpers\Helpers;
use App\Jobs\SendDailyTip;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Admin\DailyTip\UserDailyTip;
use App\Models\Admin\Notification\Notification;
use App\Models\Assessment;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

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

                        dispatch(new SendDailyTip($user['id']))
                            ->onQueue('tips');
                    }

                });

        } finally {

            optional($lock)->release();

        }

        return 0;

    }

}
