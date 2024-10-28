<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class AddDailyTipsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('user_daily_tips')->truncate();
        DB::table('daily_tips')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        $codeArray = ['sa', 'ma', 'jo', 'lu', 'ven', 'mer', 'so','de', 'dom', 'fe', 'gre', 'lun', 'nai', 'ne', 'pow', 'sp', 'tra', 'van', 'wil'];
        foreach($codeArray as $code){
            $dailyTip = [
                [
                    'title' => 'For Your Creating Order Driver',
                    'code' => $code,
                    'description' => '<p><strong>For Your Creating Order Driver :</strong></p> <p>Today, practice patience by counting to ten when feeling frustrated, allowing yourself a moment to breathe before reacting, which can temper your abruptness and improve interactions with others.</p>',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ];
            DB::table('daily_tips')->insert($dailyTip);
        }
    }
}
