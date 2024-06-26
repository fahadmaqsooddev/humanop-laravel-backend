<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coupon = [
            [
                'discount' => '0',
                'limit' => 0,
                'coupon' => 'LKDJSKSJN',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        DB::table('coupons')->insert($coupon);
    }
}
