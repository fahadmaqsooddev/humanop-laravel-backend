<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Alchemy\AlchemyCode;

class generateAlChemyCodeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:alchemyCode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will import all alchemy code data from excel file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        DB::table('alchemy_codes')->truncate();
        $csvFile = fopen(base_path("public/alch.csv"), "r");

        $firstline = true;

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {

            if ($firstline) {
                // Skip the first line
                $firstline = false;
                continue;
            }

            $number = $data[1];
            $code = $data[2];
            $image = $data[3];

            $alchemy = new AlchemyCode();

            $alchemy->number = $number;
            $alchemy->code = $code;
            $alchemy->image = $image;
            $alchemy->created_at = Carbon::today();
            $alchemy->updated_at = Carbon::today();

            $alchemy->save();

        }

        fclose($csvFile);

    }
}
