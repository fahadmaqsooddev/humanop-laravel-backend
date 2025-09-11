<?php

namespace Database\Seeders;

use App\Models\Admin\AssessmentIntro\AssessmentIntro;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Admin\Code\ResultVideo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResultVideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//        DB::table('assessment_result_videos')->truncate();

        $codeDetails = CodeDetail::allCodes();

        foreach ($codeDetails as $codeDetail) {

            ResultVideo::create([
                'code' => $codeDetail->code,
                'public_name' => $codeDetail->public_name,
                'video' => $codeDetail->video,
            ]);
        }

        $assessmentIntros = AssessmentIntro::all();

        foreach ($assessmentIntros as $assessmentIntro) {

            ResultVideo::create([
                'code' => $assessmentIntro->code,
                'public_name' => $assessmentIntro->public_name,
                'video' => $assessmentIntro->video,
            ]);

        }
    }
}
