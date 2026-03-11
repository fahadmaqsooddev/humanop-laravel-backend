<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeployScript extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'deploy:run';

    /**
     * The console command description.
     */
    protected $description = 'Run deployment shell script';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Deploy script started...");

        $scriptPath = base_path('clear.sh');

        if (!file_exists($scriptPath)) {
            $this->error("Script not found: " . $scriptPath);
            return Command::FAILURE;
        }

        $output = [];
        $returnVar = null;

        exec("bash $scriptPath 2>&1", $output, $returnVar);

        foreach ($output as $line) {
            $this->line($line);
        }

        if ($returnVar === 0) {
            $this->info("Deploy script finished successfully");
            return Command::SUCCESS;
        } else {
            $this->error("Deploy script failed");
            return Command::FAILURE;
        }
    }
}