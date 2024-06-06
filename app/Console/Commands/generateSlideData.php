<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\Admin\Slide\Slide;
use Illuminate\Support\Facades\DB;

class generateSlideData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:slide';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will import all slide data from excel file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

//        DB::table('slides')->truncate();

        $csvFile = fopen(base_path("public/slide_data.csv"), "r");

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {

            if ($firstline) {
                // Skip the first line
                $firstline = false;
                continue;
            }

            $heading = $data[1];
            $body = $data[2];
            $sub = $data[3];
            $slide_id = $data[4];
            $sub1 = $data[5];
            $sub2 = $data[6];

            $slide = new Slide();

            $slide->heading = $heading;
            $slide->body = $body;
            $slide->sub = $sub;
            $slide->sub1 = $sub1;
            $slide->sub2 = $sub2;
            $slide->slide_id = $slide_id;
            $slide->created_at = Carbon::today();
            $slide->updated_at = Carbon::today();

            $slide->save();

        }

        fclose($csvFile);
    }

}
