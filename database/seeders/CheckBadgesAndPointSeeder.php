<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Client\Gamification\GamificationBadgesAchievement;
use App\Models\Client\HumanOpPoints\HumanOpPoints;
use App\Models\User;
use Illuminate\Database\Seeder;

class CheckBadgesAndPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = User::all();

        foreach ($users as $user){

            $getAssessment = Assessment::getLatestAssessment($user->id);

            if ($getAssessment){

                GamificationBadgesAchievement::addBadgeAfterCompleteAssessment($user['id']);

                HumanOpPoints::addPointsAfterCompleteAssessment($user);

            }
        }
    }
}
