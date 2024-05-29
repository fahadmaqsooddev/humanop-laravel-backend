<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin\Question\Question;
use Illuminate\Support\Carbon;

class generateQuestionData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'questions:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will import all questions data from excel file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $csvFile = fopen(base_path("public/questions.csv"), "r");

        $firstline = false;

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {

            $q = $data[1];
            $sort = $data[2];
            $active = $data[3];
            $gender = $data[5];

            if (!$firstline) {

                $question = new Question();

                $question->question = $q;
                $question->sort = $sort;
                $question->active = $active;
                $question->gender = $gender;
                $question->gender = $gender;
                $question->created_at = Carbon::today();
                $question->updated_at = Carbon::today();

                $question->save();
            }

            $firstline = false;

        }

        fclose($csvFile);
    }
}
