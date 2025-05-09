<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HasPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('model_has_permissions')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $admins = User::getSuperAdmins();

        foreach ($admins as $admin) {

            for ($i = 1; $i <= 9; $i++) {

                DB::table('model_has_permissions')->insert([

                    'permission_id' => $i,
                    'model_type' => 'App\Models\User',
                    'model_id' => $admin['id'],
                ]);

            }

        }

    }
}
