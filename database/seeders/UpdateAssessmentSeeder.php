<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assessment;

class UpdateAssessmentSeeder extends Seeder
{
    public function run(): void
    {
        $assessments = Assessment::whereNotNull('page')
            ->where('page', '!=', 0)
            ->get();

        $updatedCount = 0; // counter

        foreach ($assessments as $assessment) {

            $webPage = (int) $assessment->page;
            $appPage = $webPage * 3;

            $assessment->update([
                'web_page' => $webPage,
                'app_page' => $appPage,
            ]);

            $updatedCount++; // increment
        }

        echo "✔ UpdateAssessmentSeeder: {$updatedCount} records updated successfully.\n";
    }
}
