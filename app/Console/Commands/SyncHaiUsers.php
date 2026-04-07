<?php

namespace App\Console\Commands;

use App\Enums\Admin\Admin;
use App\Helpers\HaiChat\HaiChatHelpers;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncHaiUsers extends Command
{

    protected $signature = 'hai:sync-users {--limit=0 : Optional max number of users to sync}';

    protected $description = 'Sync eligible users data with HAI using cursor-based fetching';

    public function handle()
    {

        $limit = (int) $this->option('limit');

        $query = User::query()->where('is_admin', 2)

            ->whereHas('haiAssessments', function ($query) {

                $query->where('page', 0);

            })

            ->orderBy('id');

        $total = (clone $query)->count();

        if ($total === 0) {

            $this->info('No users available for HAI sync.');

            return self::SUCCESS;

        }

        if ($limit > 0) {

            $total = min($total, $limit);

            $query->limit($limit);

        }

        $this->info("Starting HAI sync for {$total} user(s)...");

        $failedUsers = [];
        $syncedUsers = 0;
        $processed = 0;

        foreach ($query->cursor() as $user) {

            if ($limit > 0 && $processed >= $limit) {

                break;

            }

            $processed++;

            $userId = $user->id ?? null;

            try {

                $user->setAppends([]);

                $payload = $user->toArray();

                $payload['gender'] = (int) ($user->gender ?? 0) === Admin::IS_MALE ? 'Male' : 'Female';

                $payload['last_login'] = !empty($user->last_login) ? Carbon::parse($user->last_login)->format('m/d/Y h:i A') : null;

                $response = HaiChatHelpers::syncUserRecordWithHAi($payload);

                $isSuccessful = !empty($response) && (!isset($response['status']) || $response['status'] === true);

                if ($isSuccessful) {

                    $syncedUsers++;

                } else {

                    if ($userId !== null) {

                        $failedUsers[] = $userId;

                    }

                    Log::warning('HAI user sync failed via command', [
                        'user_id' => $userId,
                        'response' => $response,
                    ]);

                }

            } catch (\Throwable $exception) {

                if ($userId !== null) {

                    $failedUsers[] = $userId;

                }

                Log::error('HAI user sync exception via command', [
                    'user_id' => $userId,
                    'error' => $exception->getMessage(),
                ]);

            }

        }

        $failedUsers = array_values(array_unique($failedUsers));

        $failedCount = count($failedUsers);

        $this->info('HAI sync completed.');
        $this->line('Total users: ' . $processed);
        $this->line('Synced users: ' . $syncedUsers);
        $this->line('Failed users: ' . $failedCount);

        if ($failedCount > 0) {

            $this->line('Failed user IDs: ' . implode(',', $failedUsers));

        }

        return self::SUCCESS;

    }

}
