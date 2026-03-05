<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ImpactProject;

class ImpactProjectsTableSeeder extends Seeder
{
    public function run()
    {

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