<?php

namespace App\Console\Commands;

use App\Enums\Admin\Admin;
use App\Events\DailyTip\NewDailyTip;
use App\Helpers\Helpers;
use App\Jobs\SendDailyTip;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Admin\DailyTip\UserDailyTip;
use App\Models\Admin\Notification\Notification;
use App\Models\Assessment;
use Carbon\Carbon;
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

        Log::info('command start');

        $users = User::whereIn('is_admin', [Admin::IS_CUSTOMER, Admin::IS_B2B])->get();

        foreach ($users as $user) {

            Log::info('get user');

            $assessment = Assessment::where('user_id', $user['id'])->where('page', 0)->latest()->first();

            if (empty($assessment)) {
                return;
            }

            Log::info('get assessment');

            $userDailyTip = UserDailyTip::where('user_id', $user['id'])->with('dailyTip')->latest()->first();

            Log::info('get tip');

            $canUpdate = false;

            if (!empty($userDailyTip) && $user['plan_name'] == 'Core' && !empty($user['set_daily_tip_time']) && $userDailyTip['is_read'] == 1) {

                Log::info('daily tip update');

                $minutes = Helpers::explodeTimezoneWithHoursAndMinutes($user['timezone']);

                $currentTime = Carbon::now()->addMinutes($minutes)->startOfMinute();

                $setTipTimeToday = Carbon::parse($user['set_daily_tip_time'])->setDateFrom(Carbon::now())->startOfMinute();

                $nextTipTime = $currentTime->greaterThan($setTipTimeToday) ? $setTipTimeToday->copy()->addDay() : $setTipTimeToday;

                $canUpdate = $currentTime->greaterThanOrEqualTo($nextTipTime);

            }

            if (!$canUpdate) return;

            do {

                Log::info('daily tip update 01');

                $randomCode = DailyTip::randomCode($assessment);

                $newDailyTip = DailyTip::getSameCodeTips($randomCode);

                if (!$newDailyTip) {
                    break;
                }

                $latestTip = UserDailyTip::where('user_id', $user['id'])->where('daily_tip_id', $newDailyTip['id'])->latest()->first();

                if (empty($latestTip)) {

                    UserDailyTip::createUserDailyTip($user['id'], $newDailyTip['id'], $assessment['id']);

                    $message = 'Your New Daily Tip';

                    event(new NewDailyTip($user['id'], 'new daily tip', $message));

                    Notification::createNotification('Daily Tip', $message, $user['device_token'], $user['id'], 1, Admin::DAILY_TIP_NOTIFICATION, Admin::B2C_NOTIFICATION);

                    break;

                }

            } while ($latestTip && $latestTip['is_read'] == 1 && $latestTip['updated_at'] >= now()->subYear());

        }


//        $lock = Cache::lock('tips:dispatch-due', 55);
//
//        if (!$lock->get()) {
//
//            $this->info('Another dispatcher is running. Exiting.');
//
//            return 0;
//
//        }
//
//        try {
//
//            User::query()
//                ->where('is_admin', 2)
//                ->chunkById(1000, function ($users) {
//
//                    foreach ($users as $user) {
//
//                        dispatch(new SendDailyTip($user['id']))
//                            ->onQueue('tips');
//
//                    }
//
//                });
//
//        } finally {
//
//            optional($lock)->release();
//
//        }
//
//        return 0;
//
    }

}
