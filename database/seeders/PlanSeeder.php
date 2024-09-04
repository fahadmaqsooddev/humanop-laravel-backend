<?php

namespace Database\Seeders;

use App\Models\Client\Plan\Plan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plan = [

            ['plan_id' => 'price_1PuwhBRxOqsngfBOk9G5SYBo','name' => 'Freemium','billing_method' => 'month', 'interval_count' => null, 'price' => '0.00', 'currency' => 'usd','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['plan_id' => 'price_1PuwhcRxOqsngfBO7cB91rOu','name' => 'Core','billing_method' => 'month', 'interval_count' => null, 'price' => '10.00', 'currency' => 'usd','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['plan_id' => 'price_1Puwi2RxOqsngfBOKfXxaRTZ','name' => 'Premium','billing_method' => 'month', 'interval_count' => null, 'price' => '50.00', 'currency' => 'usd','updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        ];

        foreach ($plan as $pl){

            Plan::where('plan_id', $pl['plan_id'])->update($pl);

        }

        DB::table('plans')->insert($plan);
    }
}
