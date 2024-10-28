<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('action_plans')->truncate();

        $actionPlan = [
            ['public_name' => 'Aesthetic Sensibility','code' => 'van','number' => 0,'membership_level' => 'freemium','text' => 'For the next 90 days, your focus is on cultivating a deeper connection with yourself. Remember that you are uniquely designed, significant, and worthy, even if you do not currently feel this way. Your life has a purpose, and you possess a distinctive gift meant to be activated and shared as part of your life is mission.

For the next 90-days, each morning, at noon, and in the evening, “open the doors” to all four of your Energy Centers in your uniquely ordered configuration. In other words, engage in a quick activity to “open” each Energy Center. Here are examples of general activities that “open the door” to each Energy Center.
.
Emotional: Make sure you are in the presence of living things.
Instinctual: Play soft background music or light a candle.
Intellectual: Jot down some notes that are supportive for your day.
Moving: Engage in physical movement.

Consult HAi for additional activity suggestions.

After opening all Energy Center "doors," focus on activating your primary Energy Center (aka “front door”) again, while you “fuel” your two Motivational Drivers. For specific ideas on activating your primary Energy Center or fueling your drivers, consult HAi for personalized guidance.
','created_at' => now(),'updated_at' => now()]
        ];
    }
}
