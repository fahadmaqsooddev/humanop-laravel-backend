<?php

namespace Database\Seeders;

use App\Models\Admin\AssessmentIntro\AssessmentIntro;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Admin\Code\ResultVideo;
use Illuminate\Database\Seeder;

class CodeDetailAndAssessmentIntroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $videos = ResultVideo::all();

        foreach ($videos as $video) {

            CodeDetail::where('code', $video->code)->update(['video_id' => $video->id]);
        }

        foreach ($videos as $video) {

            AssessmentIntro::where('code', $video->code)->update(['video_id' => $video->id]);
        }

    }

}
