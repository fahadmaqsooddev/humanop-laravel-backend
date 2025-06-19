<?php

namespace Database\Seeders;

use App\Enums\Admin\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShareAssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('user_share_assessment')->truncate();

        $users = User::whereIn('is_admin', [Admin::IS_CUSTOMER, Admin::IS_CANDIDATE, Admin::IS_TEAM_MEMBER])->get();

        foreach ($users as $user) {

            $getRecord = User\UserShareAssessment::getSingleRecord($user['id']);

            if (empty($getRecord)) {
                User\UserShareAssessment::create([
                    'user_id' => $user['id'],
                    'interval_of_life' => Admin::SHARE_ASSESSMENT,
                    'traits' => Admin::SHARE_ASSESSMENT,
                    'motivational_driver' => Admin::SHARE_ASSESSMENT,
                    'alchemic_boundaries' => Admin::SHARE_ASSESSMENT,
                    'communication_style' => Admin::SHARE_ASSESSMENT,
                    'perception_of_life' => Admin::SHARE_ASSESSMENT,
                    'energy_pool' => Admin::SHARE_ASSESSMENT,
                ]);
            } else {
                User\UserShareAssessment::where('user_id', $user['id'])->update([
                    'interval_of_life' => Admin::SHARE_ASSESSMENT,
                    'traits' => Admin::SHARE_ASSESSMENT,
                    'motivational_driver' => Admin::SHARE_ASSESSMENT,
                    'alchemic_boundaries' => Admin::SHARE_ASSESSMENT,
                    'communication_style' => Admin::SHARE_ASSESSMENT,
                    'perception_of_life' => Admin::SHARE_ASSESSMENT,
                    'energy_pool' => Admin::SHARE_ASSESSMENT,
                ]);
            }

        }
    }
}
