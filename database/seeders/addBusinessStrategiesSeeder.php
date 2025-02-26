<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class addBusinessStrategiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//        DB::table('business_strategies')->truncate();

        $businessStrategies = [

            ['name' => 'Retail & E-Commerce','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'Food & Beverage','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'Health & Wellness','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'Technology & IT Services','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'Education & Training','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'Real Estate & Property Management','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'Finance & Legal Services','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'Manufacturing & Industrial','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'Creative & Media','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['name' => 'Travel & Hospitality','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        ];

        DB::table('business_strategies')->insert($businessStrategies);
    }
}
