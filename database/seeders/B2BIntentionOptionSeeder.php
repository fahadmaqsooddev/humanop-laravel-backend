<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class B2BIntentionOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $options = [
            'Grow My Team',
            'Improve Team Performance',
            'Improve Team Culture',
            'Scale Organization',
            'Conflict Resolution'
        ];

        foreach ($options as $option) {
            DB::table('b2b_intention_option')->insert([
                'intention_option' => $option,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
