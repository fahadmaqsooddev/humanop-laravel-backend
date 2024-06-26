<?php

namespace Database\Seeders;

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
        $hasPermission = [
            [
                'permission_id' => 1,
                'model_type' => 'App\Models\User',
                'model_id' => 1,

            ],
            [
                'permission_id' => 2,
                'model_type' => 'App\Models\User',
                'model_id' => 1,

            ],
            [
                'permission_id' => 3,
                'model_type' => 'App\Models\User',
                'model_id' => 1,

            ],
            [
                'permission_id' => 4,
                'model_type' => 'App\Models\User',
                'model_id' => 1,

            ],
            [
                'permission_id' => 5,
                'model_type' => 'App\Models\User',
                'model_id' => 1,

            ],
            [
                'permission_id' => 6,
                'model_type' => 'App\Models\User',
                'model_id' => 1,

            ],
        ];
        DB::table('model_has_permissions')->insert($hasPermission);
    }
}
