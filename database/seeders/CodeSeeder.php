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
//        DB::table('codes')->truncate();

        $codes = [
            [
                'code' => 'sa',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ma',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'jo',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'lu',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ven',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'mer',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'so',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'de',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'dom',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'fe',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'gre',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'lun',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'nai',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ne',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'pow',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'sp',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'tra',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'van',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'wil',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'g',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 's',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'c',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'em',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ins',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'int',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'mov',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('codes')->insert($codes);
    }
}
