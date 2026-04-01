<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assessment;

class FixAssessmentPageSeeder extends Seeder
{
    public function run(): void
    {
        Assessment::chunk(500, function ($assessments) {

            $updatedCount = 0;

            foreach ($assessments as $assessment) {

                // Safe casting in case values are null
                $webPage = (int) ($assessment->web_page ?? 0);
                $appPage = (int) ($assessment->app_page ?? 0);

                // Custom page logic
                if ($webPage < 3 && $appPage < 3) {
                    $page = null;
                }

                $assessment->update([
                    'page' => $page,
                ]);

                $updatedCount++;
            }

            echo "✔ FixAssessmentPageSeeder: {$updatedCount} records updated successfully.\n";
        });
    }
}