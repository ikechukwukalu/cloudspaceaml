<?php

namespace Cloudspace\AML\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Cloudspace\AML\Facades\RiskScanner;
use App\Models\User; // Or your actual User model

class ScanNewUsersJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function handle(): void
    {
        // Get users created in the last 24 hours
        $users = User::whereDate('created_at', now()->subDay())->get();

        foreach ($users as $user) {
            try {
                RiskScanner::scan($user->name, $user->bvn ?? null, $user->nin ?? null);
                Log::info("Risk scan complete for user: {$user->name}");
            } catch (\Exception $e) {
                Log::error("Risk scan failed for {$user->name}: " . $e->getMessage());
            }
        }
    }
}
