<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assessment;

class FixAssessmentPageSeeder extends Seeder
{
    public function run(): void
    {
        $totalUpdated = 0; // poore seeder ka counter

        Assessment::chunk(500, function ($assessments) use (&$totalUpdated) {

            foreach ($assessments as $assessment) {

                $webPage = (int) ($assessment->web_page ?? 0);
                $appPage = (int) ($assessment->app_page ?? 0);

                // Only update if both > 0 and both < 3
                if ($webPage > 0 && $appPage > 0 && $webPage < 3 && $appPage < 3) {

                    $assessment->update([
                        'page' => null,
                    ]);

                    $totalUpdated++; // chunk ke andar bhi increment
                }
            }
        });

        // Seeder ke end me total update print
        echo "✔ FixAssessmentPageSeeder: {$totalUpdated} records updated successfully.\n";
    }
}