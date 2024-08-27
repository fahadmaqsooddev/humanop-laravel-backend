<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\AssessmentDetail;

class generateAssessmentDetail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:assessmentDetail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will import all assessment detail data from excel file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $csvFile = fopen(base_path("public/assessment_details.csv"), "r");

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {

            if ($firstline) {
                // Skip the first line
                $firstline = false;
                continue;
            }

            $user_id = $data[1];
            $assessment_id = $data[2];
            $question = $data[3];
            $answer = $data[4];

            $assessmentDetail = new AssessmentDetail();

            $assessmentDetail->user_id = $user_id;
            $assessmentDetail->assessment_id = $assessment_id;
            $assessmentDetail->question = $question;
            $assessmentDetail->answer = $answer;
            $assessmentDetail->created_at = Carbon::today();
            $assessmentDetail->updated_at = Carbon::today();
            $assessmentDetail->save();

        }

        fclose($csvFile);
    }
}
