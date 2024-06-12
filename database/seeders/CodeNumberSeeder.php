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
        DB::table('code_numbers')->truncate();

        $numbers = [
            [
                'number' => 0
            ],
            [
                'number' => 1
            ],
            [
                'number' => 2
            ],
            [
                'number' => 3
            ],
        ];
        DB::table('code_numbers')->insert($numbers);
    }
}
