<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\SupplyStock;
use App\Models\SupplyTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SupplyLedgerCardController extends Controller
{
    /**
     * Display ledger card list (all supplies)
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $fundCluster = $request->get('fund_cluster');

        $suppliesQuery = Supply::with('category')
            ->select('supplies.*',
                DB::raw('(SELECT SUM(quantity_on_hand) FROM supply_stocks
                        WHERE supply_stocks.supply_id = supplies.supply_id) as total_stock'));

        if ($search) {
            $suppliesQuery->where(function($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('stock_no', 'like', "%{$search}%");
            });
        }

        // Get supplies that have transactions or stocks
        $suppliesQuery->whereExists(function ($query) use ($fundCluster) {
            $query->select(DB::raw(1))
                ->from('supply_stocks')
                ->whereRaw('supply_stocks.supply_id = supplies.supply_id');

            if ($fundCluster) {
                $query->where('fund_cluster', $fundCluster);
            }
        });

        $supplies = $suppliesQuery->paginate(10);

        // Get all fund clusters for filter dropdown
        $fundClusters = SupplyStock::select('fund_cluster')
            ->distinct()
            ->whereNotNull('fund_cluster')
            ->pluck('fund_cluster');

        return view('supply-ledger-cards.index', compact('supplies', 'fundClusters'));
    }

    /**
     * Display ledger card for a specific supply
     */
    public function show(Request $request, $supplyId)
    {
        $supply = Supply::with('category')->findOrFail($supplyId);
        $fundCluster = $request->get('fund_cluster', '101'); // Default to 101 if not specified

        // Get selected year (default to current year)
        $selectedYear = $request->get('year', Carbon::now()->year);

        // Get all transactions for this supply, ordered by date
        $transactions = SupplyTransaction::with(['department', 'user'])
            ->where('supply_id', $supplyId)
            ->orderBy('transaction_date')
            ->orderBy('created_at')
            ->get();

        // Get available fund clusters for this supply
        $fundClusters = SupplyStock::where('supply_id', $supplyId)
            ->select('fund_cluster')
            ->distinct()
            ->whereNotNull('fund_cluster')
            ->pluck('fund_cluster');

        // Get available years for transactions
        $availableYears = SupplyTransaction::where('supply_id', $supplyId)
            ->selectRaw('YEAR(transaction_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // If no transactions yet, add current year
        if ($availableYears->isEmpty()) {
            $availableYears = collect([Carbon::now()->year]);
        }

        // Calculate current stock
        $currentStock = SupplyStock::where('supply_id', $supplyId)
            ->where('fund_cluster', $fundCluster)
            ->sum('quantity_on_hand');

        // Get average unit cost for this supply
        $averageUnitCost = SupplyStock::where('supply_id', $supplyId)
            ->where('fund_cluster', $fundCluster)
            ->where('quantity_on_hand', '>', 0)
            ->avg('unit_cost') ?? 0;

        // Prepare the ledger card data
        $ledgerCardEntries = $this->prepareLedgerCardEntries(
            $transactions,
            $fundCluster,
            $averageUnitCost,
            $selectedYear
        );

        // --- NEW: Determine the current moving average cost ---
        $lastEntry = end($ledgerCardEntries);
        $movingAverageCost = $lastEntry['balance_unit_cost'] ?? 0;

        return view('supply-ledger-cards.show', compact(
            'supply',
            'ledgerCardEntries',
            'fundClusters',
            'fundCluster',
            'currentStock',
            'averageUnitCost',
            'availableYears',
            'selectedYear',
            'movingAverageCost'   // pass new variable to view
        ));
    }

    /**
     * Prepare ledger card entries with running balance
     */
    private function prepareLedgerCardEntries($transactions, $fundCluster, $averageUnitCost, $selectedYear)
    {
        $entries = [];
        $runningBalance = 0;
        $runningTotalCost = 0;
        $weightedAverageUnitCost = 0;

        // Filter transactions by selected year and previous years (for beginning balance)
        $yearStartDate = Carbon::createFromDate($selectedYear, 1, 1)->startOfDay();
        $yearEndDate   = Carbon::createFromDate($selectedYear, 12, 31)->endOfDay();

        // Calculate beginning balance from all transactions before selected year
        $prevYearTransactions = $transactions->filter(function ($txn) use ($yearStartDate) {
            return $txn->transaction_date < $yearStartDate;
        });

        foreach ($prevYearTransactions as $txn) {
            if ($txn->transaction_type == 'receipt') {
                $runningBalance += $txn->quantity;
                $runningTotalCost += $txn->total_cost; // Use total_cost from transaction
            } elseif ($txn->transaction_type == 'issue') {
                // For issues, calculate cost based on weighted average
                if ($runningBalance > 0) {
                    $costToDeduct = ($runningTotalCost / $runningBalance) * $txn->quantity;
                    $runningTotalCost = max(0, $runningTotalCost - $costToDeduct);
                }
                $runningBalance -= $txn->quantity;
            }
        }

        // Calculate weighted average for beginning balance
        $weightedAverageUnitCost = $runningBalance > 0 ? $runningTotalCost / $runningBalance : 0;

        // Add beginning balance entry
        $entries[] = [
            'date'               => $yearStartDate->format('Y-m-d'),
            'reference'          => 'Beginning Balance',
            'receipt_qty'        => null,
            'receipt_unit_cost'  => null,
            'receipt_total_cost' => null,
            'issue_qty'          => null,
            'issue_unit_cost'    => null,
            'issue_total_cost'   => null,
            'balance_qty'        => $runningBalance,
            'balance_unit_cost'  => $weightedAverageUnitCost,
            'balance_total_cost' => $runningTotalCost,
            'days_to_consume'    => null,
            'transaction_id'     => null,
        ];

        // Filter transactions for selected year
        $yearTransactions = $transactions->filter(function ($txn) use ($yearStartDate, $yearEndDate) {
            return $txn->transaction_date >= $yearStartDate && $txn->transaction_date <= $yearEndDate;
        });

        // Process each transaction for the selected year
        foreach ($yearTransactions as $transaction) {
            if ($transaction->transaction_type == 'receipt') {
                // For receipts, use the actual transaction values
                $receiptQty       = $transaction->quantity;
                $receiptUnitCost  = $transaction->unit_cost;
                $receiptTotalCost = $transaction->total_cost;

                $issueQty        = null;
                $issueUnitCost   = null;
                $issueTotalCost  = null;

                $runningBalance   += $receiptQty;
                $runningTotalCost += $receiptTotalCost;

            } elseif ($transaction->transaction_type == 'issue') {
                $receiptQty       = null;
                $receiptUnitCost  = null;
                $receiptTotalCost = null;

                $issueQty = $transaction->quantity;

                // Calculate the weighted average unit cost BEFORE the issue
                $weightedAverageUnitCost = $runningBalance > 0
                    ? $runningTotalCost / $runningBalance
                    : 0;

                $issueUnitCost  = $weightedAverageUnitCost;
                $issueTotalCost = $issueQty * $issueUnitCost;

                // Update running totals
                $runningBalance   -= $issueQty;
                $runningTotalCost = max(0, $runningTotalCost - $issueTotalCost);

            } else {
                // For adjustments
                $receiptQty       = null;
                $receiptUnitCost  = null;
                $receiptTotalCost = null;
                $issueQty         = null;
                $issueUnitCost    = null;
                $issueTotalCost   = null;

                $runningBalance = $transaction->balance_quantity;
                if ($runningBalance > 0 && $transaction->unit_cost) {
                    $runningTotalCost = $runningBalance * $transaction->unit_cost;
                }
            }

            // Recalculate weighted average after the transaction
            $newWeightedAverage = $runningBalance > 0
                ? $runningTotalCost / $runningBalance
                : 0;

            $entries[] = [
                'date'               => $transaction->transaction_date->format('Y-m-d'),
                'reference'          => $transaction->reference_no,
                'receipt_qty'        => $receiptQty,
                'receipt_unit_cost'  => $receiptUnitCost,
                'receipt_total_cost' => $receiptTotalCost,
                'issue_qty'          => $issueQty,
                'issue_unit_cost'    => $issueUnitCost,
                'issue_total_cost'   => $issueTotalCost,
                'balance_qty'        => $runningBalance,
                'balance_unit_cost'  => $newWeightedAverage,
                'balance_total_cost' => $runningTotalCost,
                'days_to_consume'    => $transaction->transaction_type == 'receipt'
                    ? ($transaction->days_to_consume ?? null)
                    : null,
                'transaction_id'     => $transaction->transaction_id,
            ];
        }

        return $entries;
    }

    /**
     * Export ledger card to PDF
     */
    public function exportPdf(Request $request, $supplyId)
    {
        $supply = Supply::findOrFail($supplyId);
        $fundCluster = $request->get('fund_cluster', '101');
        $selectedYear = $request->get('year', Carbon::now()->year);

        // Get all transactions for this supply
        $transactions = SupplyTransaction::with(['department', 'user'])
            ->where('supply_id', $supplyId)
            ->orderBy('transaction_date')
            ->orderBy('created_at')
            ->get();

        // Get average unit cost for this supply
        $averageUnitCost = SupplyStock::where('supply_id', $supplyId)
            ->where('fund_cluster', $fundCluster)
            ->where('quantity_on_hand', '>', 0)
            ->avg('unit_cost') ?? 0;

        // Prepare the ledger card data
        $ledgerCardEntries = $this->prepareLedgerCardEntries($transactions, $fundCluster, $averageUnitCost, $selectedYear);

        // Generate PDF using your preferred library (e.g., dompdf, barryvdh/laravel-dompdf)
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('supply-ledger-cards.pdf', compact(
            'supply',
            'ledgerCardEntries',
            'fundCluster',
            'selectedYear'
        ));

        // Return the PDF for download
        return $pdf->download("supplies-ledger-card-{$supply->stock_no}-{$selectedYear}.pdf");
    }
}
