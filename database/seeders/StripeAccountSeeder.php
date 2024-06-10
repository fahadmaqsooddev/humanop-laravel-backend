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
        DB::table('stripe_settings')->truncate();

        $stripe_account = [
            [
                'account_name' => 'M Ahtasham',
                'account_email' => 'mahtasham060@gmail.com',
                'api_key' => 'sk_test_51POCfVRxOqsngfBOEZQIBANGwXJ5gNqRHrmMQ24pke5p5sFhXlicCwsNYTS3gqZHY9z4KNEO3SqcZoAZIds0i81F00kr86cMJp',
                'public_key' => 'pk_test_51POCfVRxOqsngfBOj4nQaZn71XcfP5WueR8PinqAj2AY7qyzdHxsC2N43mwAwMbxD2Y50Bop0E488FJ0gcK61ruf00wX4q9key',
                'amount' => 500,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('stripe_settings')->insert($stripe_account);
    }
}
