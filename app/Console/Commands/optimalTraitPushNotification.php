<?php

namespace App\Console\Commands;

use App\Enums\Admin\Admin;
use App\Helpers\HaiChat\HaiChatHelpers;
use App\Helpers\Helpers;
use App\Models\Admin\Notification\Notification;
use App\Models\Assessment;
use App\Models\Notification\PushNotification;
use App\Models\User;
use App\Models\UserOptimalTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class optimalTraitPushNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:optimalTrait';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command is used for optimal trait the user in OneSignal';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::getAllClientUser();

        foreach ($users as $user) {

            Log::info(['command exe' => $user]);

            $notification = PushNotification::getSingleNotification($user['id']);

            Log::info(['command exe']);

            if ($notification['optimal_trait'] == 1) {

                $assessment = Assessment::getLatestAssessment($user['id']);

                Log::info(['assessment' => $assessment]);

                if (!empty($assessment)) {

                    $timezone = $user['timezone'];

                    $minutes = Helpers::explodeTimezoneWithHours($timezone);

                    $currentTime = Carbon::now()->addMinutes($minutes * 60);

                    Log::info(['noww' => $currentTime]);

                    $morningStart = Carbon::createFromTimeString('05:00 AM');

                    $morningEnd = Carbon::createFromTimeString('12:00 PM');

                    $afternoonStart = Carbon::createFromTimeString('12:00 PM');

                    $eveningStart = Carbon::createFromTimeString('05:00 PM');

                    $topThreeStyles = Assessment::getAllStyles($assessment);

                    $topFeatures = Assessment::getFeatures($assessment);

                    $topTwoFeatures = Assessment::getTopTwoFeatures($topFeatures['top_two_keys'], $assessment);

                    $stylesAndDrivers = array_merge($topThreeStyles, $topTwoFeatures);

                    $userOptimalTrait = UserOptimalTrait::getOptimalTrait($user['id']);

                    if (count($stylesAndDrivers) > 2) {

                        if ($currentTime->between($morningStart, $morningEnd)) {

                            $status = Admin::MORNING_STATUS;

                            $optionalTrait = $stylesAndDrivers[0]['public_name'] ?? null;

                        } elseif ($currentTime->between($afternoonStart, $eveningStart)) {

                            $status = Admin::AFTERNOON_STATUS;

                            $optionalTrait = $stylesAndDrivers[1]['public_name'] ?? null;

                        } else {

                            $status = Admin::NIGHT_STATUS;

                            $optionalTrait = $stylesAndDrivers[2]['public_name'] ?? null;

                        }

                        Log::info(['status' => $status]);

                        $message = 'Your ' . $optionalTrait . ' Optimal Trait';

                        Log::info(['message' => $message]);

                        if (empty($userOptimalTrait)) {

                            Log::info(['empty optimal trait']);

                            UserOptimalTrait::createUserOptimalTrait($optionalTrait, $user['id'], $status);

                            HaiChatHelpers::syncUserRecordWithHAi($user);

                            Helpers::OneSignalApiUsed($user['id'], 'Current Optimal Trait', $message);

                            Notification::createNotification('Optimal Trait', $message, $user['device_token'], $user['id'], 1, Admin::OPTIMAL_TRAIT,Admin::B2C_NOTIFICATION);

                        } elseif ($userOptimalTrait['status'] != $status) {

                            Log::info(['optimal trait']);

                            UserOptimalTrait::updateUserOptimalTrait($optionalTrait, $user['id'], $status);

                            HaiChatHelpers::syncUserRecordWithHAi($user);

                            Helpers::OneSignalApiUsed($user['id'], 'Current Optimal Trait', $message);

                            Notification::createNotification('Optimal Trait', $message, $user['device_token'], $user['id'], 1, Admin::OPTIMAL_TRAIT,Admin::B2C_NOTIFICATION);

                        }

                        Log::info(['done']);

                    }
                }

            }
        }
    }
}
