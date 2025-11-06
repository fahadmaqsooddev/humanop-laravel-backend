<?php

namespace App\Console\Commands;

use App\Enums\Admin\Admin;
use App\Events\DailyTip\NewDailyTip;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Admin\DailyTip\UserDailyTip;
use App\Models\Admin\Notification\Notification;
use App\Models\Assessment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class dailyTipPushNotification extends Command
{
    protected $signature = 'tips:dispatch-due';

    protected $description = 'Dispatch daily tips for schedules that are due';

    public function handle()
    {
        foreach (User::whereIn('is_admin', [Admin::IS_CUSTOMER, Admin::IS_B2B])->cursor() as $user) {

            $this->processUser($user);
        }
    }

    private function processUser($user)
    {

        $assessment = Assessment::getLatestAssessment($user->id);

        if (!$assessment) return;

        $latestTip = UserDailyTip::where('user_id', $user->id)->latest()->first();

        $currentTime = now()->setTimezone($this->extractUserTimezone($user->timezone))->startOfMinute();

        if ($this->canReceiveNewTip($user, $latestTip, $currentTime)) {
            Log::info('5');

            $this->assignNewTip($user, $assessment);
        }

    }

//    private function canReceiveNewTip($user, $latestTip, Carbon $currentTime): bool
//    {
//
//        if ($user->plan_name === 'Premium' && !empty($user->set_daily_tip_time) && !empty($latestTip) && $latestTip->is_read === 1) {
//
//            $setTipTimeToday = Carbon::parse($user->set_daily_tip_time)
//
//                ->setTimezone($currentTime->timezone)
//
//                ->setDateFrom($currentTime)
//
//                ->startOfMinute();
//
//            $nextAllowedTime = $setTipTimeToday->copy()->addDay();
//
//            return $currentTime->greaterThanOrEqualTo($nextAllowedTime);
//        }
//
//        return false;
//    }

    private function canReceiveNewTip($user, $latestTip, Carbon $currentTime): bool
    {

        if ($user->plan_name === 'Premium' && !empty($user->set_daily_tip_time) && !empty($latestTip) && $latestTip->is_read === 1) {
            if ($user->id === 2891){
                Log::info(print_r($currentTime, true));
Log::info($user->set_daily_tip_time);
Log::info($currentTime->timezone);
            }
            $setTipTimeToday = Carbon::parse($user->set_daily_tip_time, $user->timezone)

                ->setDateFrom($currentTime)

                ->startOfMinute();

            Log::info($setTipTimeToday);

            $nextAllowedTime = $currentTime->greaterThan($setTipTimeToday) ? $setTipTimeToday->copy()->addDay() : $setTipTimeToday;

            return $currentTime->greaterThanOrEqualTo($nextAllowedTime);
        }

        return false;

    }

    private function assignNewTip($user, $assessment)
    {
        Log::info('Enter assign new tip');
        $maxAttempts = 10;
        $attempts = 0;

        while ($attempts++ < $maxAttempts) {

            $randomCode = DailyTip::randomCode($assessment);

            $newTip = DailyTip::getSameCodeTips($randomCode);

            if (!$newTip) {
                continue;
            }

            // Check if this tip has been sent to this user in the last year
            $alreadySeen = UserDailyTip::where('user_id', $user->id)
                ->where('daily_tip_id', $newTip->id)
                ->where('is_read', 1)
                ->where('updated_at', '>=', now()->subYear())
                ->exists();

            if ($alreadySeen) {
                continue;
            }

            // Safe to assign tip
            UserDailyTip::createUserDailyTip($user->id, $newTip->id, $assessment->id);
            Log::info('Created tip');

            $message = 'Your New Daily Tip';

            event(new NewDailyTip($user->id, 'new daily tip', $message));

            Notification::createNotification('Daily Tip', $message, $user->device_token, $user->id, 1, Admin::DAILY_TIP_NOTIFICATION, Admin::B2C_NOTIFICATION);

            Log::info("Sent new daily tip to user {$user->id}: tip #{$newTip->id}");

            return;
        }

        Log::warning("No unique tip found for user {$user->id} after {$maxAttempts} attempts");
    }

    private function extractUserTimezone($formattedTimezone)
    {
        $parts = explode('-', $formattedTimezone);

        return trim(end($parts)); // e.g., "Asia/Karachi"
    }
}
