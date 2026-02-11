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

        // Color names mapped to hex values
        $colors = [
            'red'    => '#FF0000',
            'green'  => '#00FF00',
            'yellow' => '#FFFF00',
        ];

        foreach ($traits as $trait) {
            foreach ($colors as $name => $hex) {
                DB::table('family_matrix_configurations')->insert([
                    'grid_name'   => $trait,
                    'color_code'  => $hex,
                    'text'        => strip_tags(ucfirst($name) . ' text for ' . $trait),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }
    }
}
