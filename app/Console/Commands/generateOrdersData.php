<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\Admin\Code\Code;

class generateOrdersData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will import all order data from excel file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $csvFile = fopen(base_path("public/orders.csv"), "r");

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
            $text = $data[7];

            $code = new Code();

            $code->name = $name;
            $code->code = $code_name;
            $code->public_name = $public_name;
            $code->number = $number;
            $code->type = $type;
            $code->text = $text;
            $code->created_at = Carbon::today();
            $code->updated_at = Carbon::today();

            $code->save();

        }

        fclose($csvFile);
    }
}
