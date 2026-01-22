<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class NormalizeUserGender extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:normalize-gender';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert gender values: male → 0, female → 1';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting gender normalization...');

        $affectedRows = 0; // Track rows updated

        DB::table('users')
            ->orderBy('id')
            ->chunk(500, function ($users) use (&$affectedRows) {
                foreach ($users as $user) {
                    $newGender = null;
                    $currentGender = strtolower(trim($user->gender));

                    if ($currentGender === 'male') {
                        $newGender = 0;
                    } elseif ($currentGender === 'female') {
                        $newGender = 1;
                    }

                    if (!is_null($newGender) && $user->gender != $newGender) {
                        $updated = DB::table('users')
                            ->where('id', $user->id)
                            ->update(['gender' => $newGender]);

                        $affectedRows += $updated; // Increment affected rows
                    }
                }
            });

        $this->info("Gender normalization completed successfully.");
        $this->info("Total rows affected: {$affectedRows}");

        return Command::SUCCESS;
    }
}
