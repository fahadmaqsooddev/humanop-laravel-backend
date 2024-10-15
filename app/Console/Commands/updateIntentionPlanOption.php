<?php

namespace App\Console\Commands;

use App\Helpers\Helpers;
use App\Models\IntentionPlan\IntentionOption;
use App\Models\IntentionPlan\IntentionPlan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class updateIntentionPlanOption extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:intentionPlanOption';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $intentionMapping = [
            'Personal Growth and Development' => 1,
            'Business Optimization' => 2,
            'Relationship Optimization' => 3,
            'Career Optimization' => 4,
            'Team Optimization' => 5,
            'Health & Fitness' => 6,
        ];

            DB::transaction(function () use ($intentionMapping) {
                foreach ($intentionMapping as $stringValue => $intValue) {
                    IntentionPlan::where('ninety_day_intention', $stringValue)
                        ->update(['ninety_day_intention' => $intValue]);
                }
            });

    }
}
