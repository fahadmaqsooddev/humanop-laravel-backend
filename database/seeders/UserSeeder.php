<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        DB::table('users')->truncate();

        $users = [
            [
                'first_name' => 'admin',
                'last_name' => 'admin',
                'email' => 'admin@gmail.com',
                'is_admin' => 1,
                'password' => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'first_name' => 'user',
                'last_name' => 'user',
                'email' => 'user@gmail.com',
                'is_admin' => 2,
                'password' => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('users')->insert($users);
    }
}
