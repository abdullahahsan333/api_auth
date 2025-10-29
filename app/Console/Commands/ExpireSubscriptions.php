<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserSubscription;
use Carbon\Carbon;

class ExpireSubscriptions extends Command
{
    protected $signature = 'subscriptions:expire';
    protected $description = 'Mark expired subscriptions as expired';

    public function handle()
    {
        $today = Carbon::today();

        $expired = UserSubscription::where('status', 'active')
            ->whereDate('end_date', '<', $today)
            ->update(['status' => 'expired']);

        $this->info("Expired {$expired} subscriptions.");
    }
}
