<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin\Answer\Answer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class generateAnswerData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:answer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will import all answer data from excel file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

//        Schema::disableForeignKeyConstraints();

        DB::table('answers')->truncate();
        $csvFile = fopen(base_path("public/answers.csv"), "r");

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {

            if ($firstline) {
                // Skip the first line
                $firstline = false;
                continue;
            }

            $question_id = $data[1];
            $ans = $data[2];
            $sort = $data[3];
            $image = $data[4];

            $answer = new Answer();

            $answer->question_id = $question_id;
            $answer->answer = $ans;
            $answer->sort = $sort;
            $answer->image = $image;
            $answer->created_at = Carbon::today();
            $answer->updated_at = Carbon::today();

            $answer->save();

        }

        fclose($csvFile);
    }
}
