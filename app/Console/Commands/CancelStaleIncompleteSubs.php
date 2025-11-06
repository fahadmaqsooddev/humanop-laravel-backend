<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stripe\StripeClient;

class CancelStaleIncompleteSubs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:cancel-stale-incomplete {--hours=24}';

//     {--hours=24}
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel Stripe subscriptions that are stuck in incomplete after N hours';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(StripeClient $stripe)
    {
        $thresholdTs = now()->subHours((int)$this->option('hours'))->getTimestamp();

        // Requires Stripe Search API enabled in dashboard.
        $results = $stripe->subscriptions->search([
            'query' => "status:'incomplete' AND created<" . $thresholdTs,
            'limit' => 100,
        ]);

        foreach ($results->data as $sub) {
            $stripe->subscriptions->cancel($sub->id, []);
            $this->info("Canceled incomplete subscription {$sub->id}");
        }

        return self::SUCCESS;
    }
}
