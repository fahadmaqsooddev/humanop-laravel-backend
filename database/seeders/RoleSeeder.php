<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = [
            [
                'name' => 'super admin',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'sub admin',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'practitioner',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('roles')->insert($role);
    }
}
