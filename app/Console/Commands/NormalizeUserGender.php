<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class NormalizeUserGender extends Command
{
    protected $signature = 'users:normalize-gender';

    protected $description = 'Normalize gender values: "male"/"female" or 0/1 to 0/1, others to null.';

    public function handle()
    {
        $this->info('Starting gender normalization...');

        $affectedRows = 0;

        DB::table('users')

            ->orderBy('id')

            ->chunk(500, function ($users) use (&$affectedRows) {

                foreach ($users as $user) {

                    $newGender = null;

                    // Handle numeric strings or ints safely
                    if (is_string($user->gender) && preg_match('/^\d+$/', $user->gender)) {

                        $intVal = (int) $user->gender;

                        $newGender = in_array($intVal, [0, 1]) ? $intVal : null;

                    } elseif (is_int($user->gender)) {

                        $newGender = in_array($user->gender, [0, 1]) ? $user->gender : null;

                    } else {

                        $val = strtolower(trim((string) $user->gender));

                        $newGender = match ($val) {
                            'male' => 0,
                            'female' => 1,
                            default => null,
                        };
                    }

                    if ($user->gender !== $newGender) {

                        $updated = DB::table('users')->where('id', $user->id)->update(['gender' => $newGender]);

                        $affectedRows += $updated;
                    }
                }
            });

        $this->info('Gender normalization completed successfully.');
        $this->info("Total rows affected: {$affectedRows}");

        return Command::SUCCESS;
    }
}
