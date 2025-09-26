<?php

namespace Database\Seeders;

use App\Models\Admin\DailyTip\UserDailyTip;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DailyTipCompletedAtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $tips = UserDailyTip::all();

        foreach ($tips as $tip) {

            $tip->timestamps = false;

            $tip->tip_completed_at = $tip->updated_at;

            $tip->save();

        }

    }

}
