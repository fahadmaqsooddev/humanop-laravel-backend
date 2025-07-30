<?php

namespace Database\Seeders;

use App\Helpers\Helpers;
use App\Models\Assessment;
use App\Models\AssessmentColorCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssessmentColorCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $assessments = Assessment::where('page', 0)->get();;

        foreach ($assessments as $assessment) {

            AssessmentColorCode::deleteAssessemntColorCodeData($assessment);

            AssessmentColorCode::createStylesCodeAndColor($assessment);

            AssessmentColorCode::createFeaturesCodeAndColor($assessment);

        }
    }
}
