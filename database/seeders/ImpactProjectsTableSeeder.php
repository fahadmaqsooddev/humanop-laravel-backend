<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ImpactProject;
use Illuminate\Support\Facades\DB;

class ImpactProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('impact_projects')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $projects = [
            [
                'title' => 'Global Clean Water Initiative',
                'description' => 'Every 10k HP donation funds one month of clean water for a family in need.',
                'hp_required' => 10000,
                'verification_text' => 'This is a symbolic contribution only.',
                'status' => 1,
            ],
            [
                'title' => 'Plant 100 Trees',
                'description' => 'Help plant 100 trees in deforested areas with your HP points.',
                'hp_required' => 5000,
                'verification_text' => 'Contribution is recorded inside the app only.',
                'status' => 1,
            ],
            [
                'title' => 'Support Local Education',
                'description' => 'Donate HP to support educational materials for children.',
                'hp_required' => 8000,
                'verification_text' => 'This is a symbolic contribution only.',
                'status' => 1,
            ],
        ];

        foreach ($projects as $project) {
            ImpactProject::create($project);
        }
    }
}