<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FamilyMatrixConfigurationSeeder extends Seeder
{
    public function run(): void
    {
        $traits = [
            'Traits',
            'Motivational Driver (Pilot)',
            'Alchemy',
            'Communication Styles',
            'Energy Pool',
        ];

        $colors = [
            'red',
            'green',
            'yellow',
        ];

        foreach ($traits as $trait) {
            foreach ($colors as $color) {
                DB::table('family_matrix_configurations')->insert([
                    'grid_name'   => $trait,
                    'color_code'  => $color,
                    'text'        => ucfirst($color) . ' text for ' . $trait,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }
    }
}
