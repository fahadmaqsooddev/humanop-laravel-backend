<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class NormalizeUserGender extends Command
{
    protected $signature = 'users:normalize-gender';

    protected $description = 'Convert gender values: male → 0, female → 1, empty → null';

    public function handle()
    {
        $this->info('Starting gender normalization...');

        $affectedRows = 0;

        DB::table('users')
            ->orderBy('id')
            ->chunk(500, function ($users) use (&$affectedRows) {

                foreach ($users as $user) {

                    $currentGender = strtolower(trim((string) $user->gender));
                    $newGender = null;

                    if ($currentGender === 'male') {
                        $newGender = 0;
                    } elseif ($currentGender === 'female') {
                        $newGender = 1;
                    } elseif ($currentGender === '' || is_null($user->gender)) {
                        $newGender = null;
                    } else {
                        // koi unexpected value ho to bhi null
                        $newGender = null;
                    }

                    if ($user->gender !== $newGender) {
                        $updated = DB::table('users')
                            ->where('id', $user->id)
                            ->update(['gender' => $newGender]);

                        $affectedRows += $updated;
                    }
                }
            });

        $this->info('Gender normalization completed successfully.');
        $this->info("Total rows affected: {$affectedRows}");

        return Command::SUCCESS;
    }
}
