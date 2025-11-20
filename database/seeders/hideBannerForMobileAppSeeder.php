<?php

namespace Database\Seeders;

use App\Enums\Admin\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class hideBannerForMobileAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = User::whereIn('is_admin', [Admin::IS_CUSTOMER, Admin::IS_B2B])->get();

        foreach ($users as $user) {

            $user->premium_banner_hide = 1;

            $user->save();

        }
    }
}
