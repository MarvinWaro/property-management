<?php

namespace App\Console\Commands;

use App\Models\Supply;
use App\Models\SupplyTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateTestTransactions extends Command
{
    protected $signature = 'app:create-test-transactions';
    protected $description = 'Create test transactions for year filtering';

    public function handle()
    {
        // Get the supply
        $supply = Supply::first();

        if (!$supply) {
            $this->error('No supplies found in the database!');
            return 1;
        }

        $this->info("Using supply: {$supply->item_name} (ID: {$supply->supply_id})");

        // Set initial balance
        $currentBalance = 0;

        // Check if we need to get the latest balance
        $latestTransaction = SupplyTransaction::where('supply_id', $supply->supply_id)
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($latestTransaction) {
            $currentBalance = $latestTransaction->balance_quantity;
            $this->info("Found existing balance: {$currentBalance}");
        }

        // Make sure we have a valid department_id to use
        $departmentId = $supply->department_id ?? 1;

        // Get a valid user_id
        $userId = User::first()->id ?? 1;
        $this->info("Using user ID: {$userId}");

        // Delete existing test transactions if any
        $deletedCount = SupplyTransaction::where('reference_no', 'like', 'TEST-%')->delete();
        if ($deletedCount > 0) {
            $this->info("Deleted {$deletedCount} existing test transactions");

            // Re-fetch the current balance after deletion
            $latestTransaction = SupplyTransaction::where('supply_id', $supply->supply_id)
                ->orderBy('transaction_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->first();

            if ($latestTransaction) {
                $currentBalance = $latestTransaction->balance_quantity;
                $this->info("Updated balance after deletion: {$currentBalance}");
            } else {
                $currentBalance = 0;
                $this->info("No transactions remaining, balance reset to 0");
            }
        }

        // 2024 Transactions

        // Transaction 1 (2024 receipt)
        $transaction1 = new SupplyTransaction();
        $transaction1->supply_id = $supply->supply_id;
        $transaction1->transaction_type = 'receipt';
        $transaction1->transaction_date = Carbon::createFromDate(2024, 5, 1);
        $transaction1->reference_no = 'TEST-2024-05-001';
        $transaction1->quantity = 10;
        $transaction1->unit_cost = 20.00;
        $transaction1->total_cost = 10 * 20.00;
        $transaction1->department_id = $departmentId;
        $transaction1->user_id = $userId;
        $currentBalance += 10;
        $transaction1->balance_quantity = $currentBalance;
        $transaction1->save();

        $this->info("Transaction 1 saved (2024-05 receipt). Current balance: {$currentBalance}");

        // Transaction 2 (2024 issue)
        $transaction2 = new SupplyTransaction();
        $transaction2->supply_id = $supply->supply_id;
        $transaction2->transaction_type = 'issue';
        $transaction2->transaction_date = Carbon::createFromDate(2024, 6, 15);
        $transaction2->reference_no = 'TEST-2024-06-001';
        $transaction2->quantity = 3;
        $transaction2->unit_cost = 20.00;
        $transaction2->total_cost = 3 * 20.00;
        $transaction2->department_id = $departmentId;
        $transaction2->user_id = $userId;
        $currentBalance -= 3;
        $transaction2->balance_quantity = $currentBalance;
        $transaction2->save();

        $this->info("Transaction 2 saved (2024-06 issue). Current balance: {$currentBalance}");

        // 2025 Transactions

        // Transaction 3 (2025 receipt)
        $transaction3 = new SupplyTransaction();
        $transaction3->supply_id = $supply->supply_id;
        $transaction3->transaction_type = 'receipt';
        $transaction3->transaction_date = Carbon::createFromDate(2025, 2, 10);
        $transaction3->reference_no = 'TEST-2025-02-001';
        $transaction3->quantity = 5;
        $transaction3->unit_cost = 22.00;
        $transaction3->total_cost = 5 * 22.00;
        $transaction3->department_id = $departmentId;
        $transaction3->user_id = $userId;
        $currentBalance += 5;
        $transaction3->balance_quantity = $currentBalance;
        $transaction3->save();

        $this->info("Transaction 3 saved (2025-02 receipt). Current balance: {$currentBalance}");

        // Transaction 4 (2025 issue)
        $transaction4 = new SupplyTransaction();
        $transaction4->supply_id = $supply->supply_id;
        $transaction4->transaction_type = 'issue';
        $transaction4->transaction_date = Carbon::createFromDate(2025, 3, 20);
        $transaction4->reference_no = 'TEST-2025-03-001';
        $transaction4->quantity = 2;
        $transaction4->unit_cost = 22.00;
        $transaction4->total_cost = 2 * 22.00;
        $transaction4->department_id = $departmentId;
        $transaction4->user_id = $userId;
        $currentBalance -= 2;
        $transaction4->balance_quantity = $currentBalance;
        $transaction4->save();

        $this->info("Transaction 4 saved (2025-03 issue). Current balance: {$currentBalance}");

        // 2026 Transactions

        // Transaction 5 (2026 receipt)
        $transaction5 = new SupplyTransaction();
        $transaction5->supply_id = $supply->supply_id;
        $transaction5->transaction_type = 'receipt';
        $transaction5->transaction_date = Carbon::createFromDate(2026, 4, 5);
        $transaction5->reference_no = 'TEST-2026-04-001';
        $transaction5->quantity = 15;
        $transaction5->unit_cost = 25.00;
        $transaction5->total_cost = 15 * 25.00;
        $transaction5->department_id = $departmentId;
        $transaction5->user_id = $userId;
        $currentBalance += 15;
        $transaction5->balance_quantity = $currentBalance;
        $transaction5->save();

        $this->info("Transaction 5 saved (2026-04 receipt). Current balance: {$currentBalance}");

        // Transaction 6 (2026 issue)
        $transaction6 = new SupplyTransaction();
        $transaction6->supply_id = $supply->supply_id;
        $transaction6->transaction_type = 'issue';
        $transaction6->transaction_date = Carbon::createFromDate(2026, 7, 10);
        $transaction6->reference_no = 'TEST-2026-07-001';
        $transaction6->quantity = 8;
        $transaction6->unit_cost = 25.00;
        $transaction6->total_cost = 8 * 25.00;
        $transaction6->department_id = $departmentId;
        $transaction6->user_id = $userId;
        $currentBalance -= 8;
        $transaction6->balance_quantity = $currentBalance;
        $transaction6->save();

        $this->info("Transaction 6 saved (2026-07 issue). Current balance: {$currentBalance}");

        // 2027 Transactions

        // Transaction 7 (2027 receipt)
        $transaction7 = new SupplyTransaction();
        $transaction7->supply_id = $supply->supply_id;
        $transaction7->transaction_type = 'receipt';
        $transaction7->transaction_date = Carbon::createFromDate(2027, 3, 15);
        $transaction7->reference_no = 'TEST-2027-03-001';
        $transaction7->quantity = 20;
        $transaction7->unit_cost = 27.50;
        $transaction7->total_cost = 20 * 27.50;
        $transaction7->department_id = $departmentId;
        $transaction7->user_id = $userId;
        $currentBalance += 20;
        $transaction7->balance_quantity = $currentBalance;
        $transaction7->save();

        $this->info("Transaction 7 saved (2027-03 receipt). Current balance: {$currentBalance}");

        // Transaction 8 (2027 issue)
        $transaction8 = new SupplyTransaction();
        $transaction8->supply_id = $supply->supply_id;
        $transaction8->transaction_type = 'issue';
        $transaction8->transaction_date = Carbon::createFromDate(2027, 6, 22);
        $transaction8->reference_no = 'TEST-2027-06-001';
        $transaction8->quantity = 12;
        $transaction8->unit_cost = 27.50;
        $transaction8->total_cost = 12 * 27.50;
        $transaction8->department_id = $departmentId;
        $transaction8->user_id = $userId;
        $currentBalance -= 12;
        $transaction8->balance_quantity = $currentBalance;
        $transaction8->save();

        $this->info("Transaction 8 saved (2027-06 issue). Final balance: {$currentBalance}");

        $this->info("\nTest transactions created successfully across 4 years (2024-2027)!");
        $this->info("To test year filtering, go to:");
        $this->info("1. /stock-cards/{$supply->supply_id}?year=2024");
        $this->info("2. /stock-cards/{$supply->supply_id}?year=2025");
        $this->info("3. /stock-cards/{$supply->supply_id}?year=2026");
        $this->info("4. /stock-cards/{$supply->supply_id}?year=2027");
        $this->info("5. /supply-ledger-cards/{$supply->supply_id}?year=2024");
        $this->info("6. /supply-ledger-cards/{$supply->supply_id}?year=2025");
        $this->info("7. /supply-ledger-cards/{$supply->supply_id}?year=2026");
        $this->info("8. /supply-ledger-cards/{$supply->supply_id}?year=2027");

        return 0;
    }
}
