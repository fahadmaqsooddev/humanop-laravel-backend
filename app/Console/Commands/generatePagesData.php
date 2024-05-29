<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin\Pages\Page;
use Illuminate\Support\Carbon;

class generatePagesData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:page';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will import all pages data from excel file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $csvFile = fopen(base_path("public/pages.csv"), "r");

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {

            if ($firstline) {
                // Skip the first line
                $firstline = false;
                continue;
            }

            $name = $data[1];
            $title = $data[2];
            $meta_des = $data[3];
            $meta_key = $data[4];
            $text = $data[5];

            $page = new Page();

            $page->name = $name;
            $page->title = $title;
            $page->meta_description = $meta_des;
            $page->meta_key = $meta_key;
            $page->text = $text;
            $page->created_at = Carbon::today();
            $page->updated_at = Carbon::today();

            $page->save();

        }

        fclose($csvFile);
    }
}
