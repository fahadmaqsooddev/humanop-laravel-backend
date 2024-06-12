<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CodeNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        DB::table('code_numbers')->truncate();

        $numbers = [
            [
                'number' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'number' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'number' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'number' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('code_numbers')->insert($numbers);
    }
}
