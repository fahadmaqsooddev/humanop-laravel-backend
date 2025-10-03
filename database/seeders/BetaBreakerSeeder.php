<?php

namespace Database\Seeders;

use App\Enums\Admin\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BetaBreakerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = User::get();

        foreach ($users as $user) {

            if ($user['beta_breaker_club'] != Admin::BETA_BREAKER_CLUB){

                $user->beta_breaker_club = Admin::BETA_BREAKER_CLUB;

                $user->save();

            }

        }

    }
}
