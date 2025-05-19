<?php

namespace App\Console\Commands;

use App\Models\SupplyTransaction;
use Illuminate\Console\Command;

class CleanupTestTransactions extends Command
{
    protected $signature = 'app:cleanup-test-transactions';
    protected $description = 'Remove test transactions for year filtering';

    public function handle()
    {
        $count = SupplyTransaction::where('reference_no', 'like', 'TEST-%')->delete();
        $this->info("Deleted {$count} test transactions");

        return 0;
    }
}
