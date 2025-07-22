<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreditPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('credit_plans')->truncate();

        $data = [
            ['price' => 05, 'credits' => 25, 'updated_at' => Carbon::now(), 'created_at' => Carbon::now()],
            ['price' => 10, 'credits' => 50, 'updated_at' => Carbon::now(), 'created_at' => Carbon::now()],
            ['price' => 20, 'credits' => 110, 'updated_at' => Carbon::now(), 'created_at' => Carbon::now()],
            ['price' => 30, 'credits' => 175, 'updated_at' => Carbon::now(), 'created_at' => Carbon::now()],
            ['price' => 50, 'credits' => 300, 'updated_at' => Carbon::now(), 'created_at' => Carbon::now()],
            ['price' => 100, 'credits' => 600, 'updated_at' => Carbon::now(), 'created_at' => Carbon::now()]
        ];

        DB::table('credit_plans')->insert($data);
    }
}
