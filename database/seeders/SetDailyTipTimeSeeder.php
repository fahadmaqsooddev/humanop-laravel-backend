<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SetDailyTipTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {

            if ($user['plan_name'] === 'Core') {

                $user->set_daily_tip_time = '12:00:00';
                $user->save();
            }
        }
    }
}
