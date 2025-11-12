<?php

namespace Database\Seeders;

use App\Enums\Admin\Admin;
use App\Models\Client\Plan\Plan;
use App\Models\Client\Point\Point;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class HaiCreditsForPremiumUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach (User::whereIn('is_admin', [Admin::IS_CUSTOMER, Admin::IS_B2B])->cursor() as $user) {

            $this->processUser($user);

        }

    }

    private function processUser($user)
    {

        $subscription = $user->subscription('default');

        if ($subscription && $subscription->stripe_status == 'active') {

            $priceId = $subscription->stripe_price;

            $planName = Plan::singlePlan($priceId)?->key;

            if ($planName && in_array($planName, ['premium_monthly', 'premium_yearly', 'premium_lifetime'])) {

                Point::updatePoint($user->id, Admin::PREMIUM_LIFETIME_CREDITS);

                $user->reset_hai_credit = Carbon::now();

                $user->save();

            }

        } else {

            if (in_array($user['plan'], ['premium_monthly', 'premium_yearly', 'premium_lifetime'])) {

                Point::updatePoint($user->id, Admin::PREMIUM_LIFETIME_CREDITS);

                $user->reset_hai_credit = Carbon::now();

                $user->save();

            }

        }

    }


}
