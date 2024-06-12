<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('codes')->truncate();

        $codes = [
            [
                'code_name' => 'sa'
            ],
            [
                'code_name' => 'ma'
            ],
            [
                'code_name' => 'jo'
            ],
            [
                'code_name' => 'lu'
            ],
            [
                'code_name' => 'ven'
            ],
            [
                'code_name' => 'mer'
            ],
            [
                'code_name' => 'so'
            ],
            [
                'code_name' => 'de'
            ],
            [
                'code_name' => 'dom'
            ],
            [
                'code_name' => 'fe'
            ],
            [
                'code_name' => 'gre'
            ],
            [
                'code_name' => 'lun'
            ],
            [
                'code_name' => 'nai'
            ],
            [
                'code_name' => 'ne'
            ],
            [
                'code_name' => 'pow'
            ],
            [
                'code_name' => 'sp'
            ],
            [
                'code_name' => 'tra'
            ],
            [
                'code_name' => 'van'
            ],
            [
                'code_name' => 'wil'
            ],
            [
                'code_name' => 'g'
            ],
            [
                'code_name' => 's'
            ],
            [
                'code_name' => 'c'
            ],
            [
                'code_name' => 'em'
            ],
            [
                'code_name' => 'ins'
            ],
            [
                'code_name' => 'int'
            ],
            [
                'code_name' => 'mov'
            ],
        ];

        DB::table('codes')->insert($codes);
    }
}
