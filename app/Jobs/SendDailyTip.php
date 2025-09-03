<?php

namespace App\Jobs;

use App\Enums\Admin\Admin;
use App\Events\DailyTip\NewDailyTip;
use App\Helpers\Helpers;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Admin\DailyTip\UserDailyTip;
use App\Models\Admin\Notification\Notification;
use App\Models\Assessment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\ThrottlesExceptions;
use Illuminate\Support\Facades\Cache;

class SendDailyTip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $tries = 5;
    public $backoff = [30, 60, 120, 300];
    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;

    }

    public function middleware()
    {
        // Avoid job storms on a flaky provider
        return [new ThrottlesExceptions(50, 60)];
    }

    public function handle()
    {

        $lock = Cache::lock("tips:send:{$this->userId}", 55);
        if (!$lock->get()) return;

        try {

            $user = User::getSingleUser($this->userId);
            if (!$user) return;

            $assessment = Assessment::getLatestAssessment($user['id']);

            if (empty($assessment)) {
                return;
            }

            $userDailyTip = UserDailyTip::where('user_id', $user['id'])->with('dailyTip')->latest()->first();

            $canUpdate = false;

            if (!empty($userDailyTip) && $user['plan_name'] == 'Core' && !empty($user['set_daily_tip_time']) && $userDailyTip['is_read'] == 1) {

                $minutes = Helpers::explodeTimezoneWithHoursAndMinutes($user['timezone']);

                $currentTime = Carbon::now()->addMinutes($minutes)->startOfMinute();

                $setTipTimeToday = Carbon::parse($user['set_daily_tip_time'])->setDateFrom(Carbon::now())->startOfMinute();

                $nextTipTime = $currentTime->greaterThan($setTipTimeToday) ? $setTipTimeToday->copy()->addDay() : $setTipTimeToday;

                $canUpdate = $currentTime->greaterThanOrEqualTo($nextTipTime);

            }

            if (!$canUpdate) return;

            do {

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

        } finally {

            optional($lock)->release();

        }

    }

}
