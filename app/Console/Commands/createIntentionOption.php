<?php

namespace App\Console\Commands;

use App\Helpers\Helpers;
use App\Models\IntentionPlan\Intention;
use App\Models\IntentionPlan\IntentionOption;
use http\Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class createIntentionOption extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:intentionOption';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command makes the new intention options';

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle()
    {
        $intentionOptions = ['Personal Growth and Development','Business Optimization','Relationship Optimization','Career Optimization','Team Optimization','Health & Fitness'];

            DB::transaction(function () use ($intentionOptions) {
                foreach ($intentionOptions as $option) {
                    IntentionOption::create(['description' => $option]);
                }
            });

    }
}
