<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
class generateUserData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will import all users data from excel file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $csvFile = fopen(base_path("public/users.csv"), "r");

        $firstline = false;
        $i = 0;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            $i++;
            $email = 'email'.$i.'@gmail.com';
            $firstName = $data[1];
            $lastName = $data[2];
            $phone = $data[6];
            $gender = $data[9];


            if (!$firstline) {
                $user = new User();
                $user->first_name = $firstName;
                $user->last_name = $lastName;
                $user->email = $email;
                $user->password = '$2y$10$LhLA6OfEzZVPT7c1PdbEE.muyAsTgpjw.Dvrq1/aJtX83GxG.nn2a';
                $user->phone = $phone;
                $user->age_min = 10;
                $user->age_max = 100;
                $user->gender = $gender;
                $user->signup_date = Carbon::today();
                $user->last_login = Carbon::today();
                $user->status = 1;
                $user->is_admin = 2;
                $user->created_at = Carbon::today();
                $user->updated_at = Carbon::today();

                $user->save();

            }

            $firstline = false;

        }

        fclose($csvFile);
    }
}
