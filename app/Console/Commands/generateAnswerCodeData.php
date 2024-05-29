<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin\AnswerCode\AnswerCode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class generateAnswerCodeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'answer:code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will import all answer code data from excel file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Schema::disableForeignKeyConstraints();

        $csvFile = fopen(base_path("public/ans_codes.csv"), "r");

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {

            if ($firstline) {
                // Skip the first line
                $firstline = false;
                continue;
            }

            $ans_id = $data[1];
            $code = $data[3];
            $number = $data[4];

            $ans_code = new AnswerCode();

            $ans_code->answer_id = $ans_id;
            $ans_code->code = $code;
            $ans_code->number = $number;
            $ans_code->created_at = Carbon::today();
            $ans_code->updated_at = Carbon::today();

            $ans_code->save();

        }

        fclose($csvFile);
    }
}
