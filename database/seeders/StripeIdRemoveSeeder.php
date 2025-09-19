<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StripeIdRemoveSeeder extends Seeder
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

            if (!empty($user->stripe_id) || !empty($user->b2c_stripe_id)) {

                $user->stripe_id = null;
                $user->b2c_stripe_id = null;

                $user->save();
            }
        }

    }
}
