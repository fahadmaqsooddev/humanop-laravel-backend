<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Assessment;
use Carbon\Carbon;
class AssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    $assessments=Assessment::where('reset_assessment',1)->get();

    foreach ($assessments as $assessment) {
      
        $assessment->update([
            
            'after_reset_assessment_updated_at' => Carbon::parse($assessment->updated_at)->format('Y-m-d H:i:s'),
        ]);
    }
    }
}
