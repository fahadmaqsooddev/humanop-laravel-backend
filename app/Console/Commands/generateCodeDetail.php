<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\Admin\Code\CodeDetail;
use Illuminate\Support\Facades\DB;

class generateCodeDetail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:codeDetail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will import all code detail data from excel file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::table('code_details')->truncate();
        
        $csvFile = fopen(base_path("public/code_details.csv"), "r");

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {

            if ($firstline) {
                // Skip the first line
                $firstline = false;
                continue;
            }

            $name = $data[1];
            $code_name = $data[2];
            $public_name = $data[3];
            $number = $data[4];
            $type = $data[5];
            $text = $data[6];
            $video = $data[9];
            $p_name = $data[10];

            $code = new CodeDetail();

            $code->name = $name;
            $code->code = $code_name;
            $code->public_name = $public_name;
            $code->number = $number;
            $code->type = $type;
            $code->text = $text;
            $code->video = $video;
            $code->p_name = $p_name;
            $code->created_at = Carbon::today();
            $code->updated_at = Carbon::today();

            $code->save();

        }

        fclose($csvFile);
    }

}
