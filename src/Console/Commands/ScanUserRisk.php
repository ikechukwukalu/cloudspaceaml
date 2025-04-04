<?php

namespace Cloudspace\AML\Console\Commands;

use Illuminate\Console\Command;
use Cloudspace\AML\Facades\RiskScanner;

class ScanUserRisk extends Command
{
    protected $signature = 'aml:scan-user {name} {--bvn=} {--nin=}';

    protected $description = 'Scan a user by name and PII for sanctions or red flags';

    public function handle()
    {
        $name = $this->argument('name');
        $bvn = $this->option('bvn');
        $nin = $this->option('nin');

        $this->info("Scanning risk profile for {$name}...");

        $result = RiskScanner::scan($name, $bvn, $nin);

        $this->info("Risk Level: " . strtoupper($result->risk_level));
        $this->info("Matches found:");

        foreach ($result->matches as $match) {
            $this->line("- {$match->source} :: {$match->description} [Confidence: {$match->confidence}%]");
        }
    }
}
