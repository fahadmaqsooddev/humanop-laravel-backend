<?php

namespace App\Console\Commands;

use App\Enums\Admin\Admin;
use App\Events\DailyTip\NewDailyTip;
use App\Helpers\Helpers;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Admin\DailyTip\UserDailyTip;
use App\Models\Admin\Notification\Notification;
use App\Models\Assessment;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Console\Command;

class dailyTipPushNotification extends Command
{

    protected $signature = 'tips:dispatch-due';

    protected $description = 'Dispatch daily tips for schedules that are due';

    public function handle()
    {

        $users = User::whereIn('is_admin', [Admin::IS_CUSTOMER, Admin::IS_B2B])->get();

        foreach ($users as $user) {

            $assessment = Assessment::where('user_id', $user['id'])->where('page', 0)->latest()->first();

            if (!empty($assessment)) {

                $userDailyTip = UserDailyTip::where('user_id', $user['id'])->with('dailyTip')->latest()->first();

                $canUpdate = false;

                if (!empty($userDailyTip) && $user['plan_name'] == 'Premium' && !empty($user['set_daily_tip_time']) && $userDailyTip['is_read'] == 1) {

                    $minutes = Helpers::explodeTimezoneWithHoursAndMinutes($user['timezone']);

                    $currentTime = Carbon::now()->addMinutes($minutes)->startOfMinute();

                    $setTipTimeToday = Carbon::parse($user['set_daily_tip_time'])->setDateFrom(Carbon::now())->startOfMinute();

                    $nextTipTime = $currentTime->greaterThan($setTipTimeToday) ? $setTipTimeToday->copy()->addDay() : $setTipTimeToday;

                    $canUpdate = $currentTime->greaterThanOrEqualTo($nextTipTime);

                }

                if ($canUpdate) {

                    do {

                        $randomCode = DailyTip::randomCode($assessment);

                        $newDailyTip = DailyTip::getSameCodeTips($randomCode);

                        if ($newDailyTip) {

                            $latestTip = UserDailyTip::where('user_id', $user['id'])->where('daily_tip_id', $newDailyTip['id'])->latest()->first();

                            if (empty($latestTip)) {

                                $getLatestTip = UserDailyTip::where('user_id', $user['id'])->latest()->first();

                                if ($getLatestTip['updated_at']->startOfMinute() != Carbon::now()->startOfMinute()) {

                                    UserDailyTip::createUserDailyTip($user['id'], $newDailyTip['id'], $assessment['id']);

                                    $message = 'Your New Daily Tip';

                                    event(new NewDailyTip($user['id'], 'new daily tip', $message));

                                    Notification::createNotification('Daily Tip', $message, $user['device_token'], $user['id'], 1, Admin::DAILY_TIP_NOTIFICATION, Admin::B2C_NOTIFICATION);
                                }

                            }

                        }

                    } while ($latestTip && $latestTip['is_read'] == 1 && $latestTip['updated_at'] >= now()->subYear());

                }

            }
        }

    }

}
