<?php

namespace App\Console\Commands;

use App\Enums\Admin\Admin;
use App\Helpers\ActivityLogs\ActivityLogger;
use App\Helpers\HaiChat\HaiChatHelpers;
use App\Models\Admin\Notification\Notification;
use App\Models\Assessment;
use App\Models\Notification\PushNotification;
use App\Models\User;
use App\Models\UserOptimalTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\v4\OneSignalServices\OneSignalService;

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
        foreach (User::getAllClientUserQuery()->cursor() as $user) {
            $this->processUser($user);
        }
    }

    private function processUser($user)
    {
        $notification = PushNotification::getSingleNotification($user->id);

        if (empty($notification) || $notification['optimal_trait'] != 1) {

            return;
        }

        $assessment = Assessment::getLatestAssessment($user->id);

        if (empty($assessment)) {

            return;
        }

        $userTime = now()->setTimezone($this->extractUserTimezone($user->timezone))->startOfMinute();

        $topStyles = Assessment::getAllStyles($assessment, $user);

        $topFeatures = Assessment::getTopTwoFeatures(Assessment::getFeatures($assessment)['top_two_keys'], $assessment);

        $traits = array_merge($topStyles, $topFeatures);

        if (count($traits) <= 2) {

            return;
        }

        [$status, $optimalTrait] = $this->determineTraitAndStatus($userTime, $traits);

        $message = "Your {$optimalTrait} Optimal Trait";

        $existingTrait = UserOptimalTrait::getOptimalTrait($user->id);

        if (empty($existingTrait)) {

            UserOptimalTrait::createUserOptimalTrait($optimalTrait, $user->id, $status);

            ActivityLogger::addLog('Optimal Trait', "Your Current Optimal Trait is {$optimalTrait}");

        } elseif ($existingTrait['status'] !== $status) {

            UserOptimalTrait::updateUserOptimalTrait($optimalTrait, $user->id, $status);

            ActivityLogger::addLog('Optimal Trait', "Your Current Optimal Trait is {$optimalTrait}");

        } else {

            return; // No update needed
        }

        HaiChatHelpers::syncUserRecordWithHAi($user);

//        Helpers::OneSignalApiUsed($user->id, 'Current Optimal Trait', $message);

        Notification::createNotification('Optimal Trait', $message, $user->device_token, $user->id, 1, Admin::OPTIMAL_TRAIT, Admin::B2C_NOTIFICATION);

        OneSignalService::sendNotification($user->id, 'Optimal Trait', $message);

        Log::info("Updated optimal trait for user {$user->id}: {$optimalTrait} ({$status})");
    }

    private function determineTraitAndStatus(Carbon $userTime, array $traits): array
    {
        if ($userTime->between(
            Carbon::createFromTimeString('05:00 AM', $userTime->timezone),
            Carbon::createFromTimeString('12:00 PM', $userTime->timezone)
        )) {

            return [Admin::MORNING_STATUS, $traits[0]['public_name'] ?? null];
        }

        if ($userTime->between(
            Carbon::createFromTimeString('12:00 PM', $userTime->timezone),
            Carbon::createFromTimeString('05:00 PM', $userTime->timezone)
        )) {

            return [Admin::AFTERNOON_STATUS, $traits[1]['public_name'] ?? null];
        }

        return [Admin::NIGHT_STATUS, $traits[2]['public_name'] ?? null];
    }

    private function extractUserTimezone($formattedTimezone)
    {
        $parts = explode('-', $formattedTimezone);

        return trim(end($parts)); // e.g., "Asia/Karachi"
    }
}
