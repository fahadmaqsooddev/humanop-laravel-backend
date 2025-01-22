<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompleteRegisterStepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allUsers = User::getAllClientUser();

        foreach ($allUsers as $user)
        {
            if ($user && $user['email_verified_at'] != null && $user['date_of_birth'] != null && $user['gender'] != null)
            {
                $user->update(['step' => 3]);
            }
        }
    }
}
