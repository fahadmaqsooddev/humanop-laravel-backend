<?php

namespace Database\Seeders;

use App\Helpers\HaiChat\HaiChatHelpers;
use App\Models\Assessment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SyncUserRecordWithHAiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::get();

        foreach ($users as $user) {

            $assessment = Assessment::getLatestAssessment($user['id']);

            if (!empty($assessment)){

                HaiChatHelpers::syncUserRecordWithHAi($user);

            }

        }
    }
}
