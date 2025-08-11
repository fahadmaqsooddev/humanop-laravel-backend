<?php

namespace Database\Seeders;

use App\Enums\Admin\Admin;
use App\Models\B2B\B2BBusinessCandidates;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FirstTimeSharedDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $getRecords = B2BBusinessCandidates::all();

        foreach ($getRecords as $data) {

            $data->first_time_share_data = Admin::FIRST_TIME_SHARED_DATA;

            $data->save();

        }
    }
}
