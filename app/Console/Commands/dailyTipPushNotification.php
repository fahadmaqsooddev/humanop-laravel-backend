<?php

namespace App\Console\Commands;

use App\Enums\Admin\Admin;
use App\Events\DailyTip\NewDailyTip;
use App\Helpers\Helpers;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Admin\DailyTip\UserDailyTip;
use App\Models\Admin\Notification\Notification;
use App\Models\Assessment;
use App\Models\User;
use Illuminate\Console\Command;

class dailyTipPushNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:dailyTipPushNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command makes for daily tip notification send.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $users = User::getAllClientUser();

        foreach ($users as $user) {

            $assessment = Assessment::getLatestAssessment($user['id']);

            if (!empty($assessment)) {

                $userDailyTip = UserDailyTip::where('user_id', $user['id'])->with('dailyTip')->latest()->first();

                if (!empty($userDailyTip) && $userDailyTip['is_read'] == 1 && $userDailyTip['updated_at'] < now()->subDay()) {

                    do {

                        $randomCode = DailyTip::randomCode($assessment);

                        $newDailyTip = DailyTip::getSameCodeTips($randomCode);

                        if ($newDailyTip) {

                            $latestTip = UserDailyTip::where('user_id', $user['id'])
                                ->where('daily_tip_id', $newDailyTip['id'])
                                ->latest()
                                ->first();

                            if (empty($latestTip)) {

                                UserDailyTip::create([
                                    'user_id' => $user['id'],
                                    'daily_tip_id' => $newDailyTip['id'],
                                    'assessment_id' => $assessment['id'],
                                    'updated_at' => $userDailyTip['updated_at']->addHours(24)
                                ]);

                                $message = 'Your New Daily Tip';

                                event(new NewDailyTip($user['id'], 'new daily tip', $message));

                                Helpers::OneSignalApiUsed($user['id'], 'new daily tip', $message);

                                Notification::createNotification('Daily Tip', $message, $user['device_token'], $user['id'], 1, Admin::DAILY_TIP_NOTIFICATION,Admin::B2C_NOTIFICATION);

                            }

                        }

                    } while ($newDailyTip && $latestTip && $latestTip['is_read'] == 1 && $latestTip['updated_at'] >= now()->subYear());

                }

            }

        }

    }
}
