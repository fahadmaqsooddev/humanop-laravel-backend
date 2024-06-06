<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\Admin\Slide\SlideMedia;
class generateSlideMediaData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:slideMedia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command will import all slode media data from excel file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $csvFile = fopen(base_path("public/media.csv"), "r");

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {

            if ($firstline) {
                // Skip the first line
                $firstline = false;
                continue;
            }

            $image = $data[1];
            $slide_id = $data[2];

            $slide = new SlideMedia();

            $slide->image = $image;
            $slide->slide_id = $slide_id;
            $slide->created_at = Carbon::today();
            $slide->updated_at = Carbon::today();

            $slide->save();

        }

        fclose($csvFile);

    }
}
