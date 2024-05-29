<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StripeAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stripe_account = [
            [
                'account_name' => 'M Ahtasham',
                'account_email' => 'mahtasham060@gmail.com',
                'api_key' => 123456789,
                'public_key' => 123456789,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('stripe_settings')->insert($stripe_account);
    }
}
