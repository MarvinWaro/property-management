<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\SupplyStock;
use App\Models\SupplyTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockCardController extends Controller
{
    /**
     * Display stock card list (all supplies)
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

        return view('stock-cards.index', compact('supplies', 'fundClusters'));
    }

    /**
     * Display stock card for a specific supply
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

        // Prepare the stock card data
        $stockCardEntries = $this->prepareStockCardEntries($transactions, $fundCluster, $selectedYear);

        return view('stock-cards.show', compact(
            'supply',
            'stockCardEntries',
            'fundClusters',
            'fundCluster',
            'currentStock',
            'availableYears',
            'selectedYear'
        ));
    }

    /**
     * Prepare stock card entries with running balance
     */
    private function prepareStockCardEntries($transactions, $fundCluster, $selectedYear)
    {
        $entries = [];
        $runningBalance = 0;
        $hasBeginningBalance = false;

        // Filter transactions by selected year and previous years (for beginning balance)
        $yearStartDate = Carbon::createFromDate($selectedYear, 1, 1)->startOfDay();
        $yearEndDate = Carbon::createFromDate($selectedYear, 12, 31)->endOfDay();

        // Calculate beginning balance from all transactions before selected year
        $prevYearTransactions = $transactions->filter(function ($txn) use ($yearStartDate) {
            return $txn->transaction_date < $yearStartDate;
        });

        foreach ($prevYearTransactions as $txn) {
            if ($txn->transaction_type == 'receipt') {
                $runningBalance += $txn->quantity;
            } elseif ($txn->transaction_type == 'issue') {
                $runningBalance -= $txn->quantity;
            }
        }

        // Add beginning balance entry for selected year
        $entries[] = [
            'date' => $yearStartDate->format('Y-m-d'),
            'reference' => 'Beginning Balance',
            'receipt_qty' => null,
            'issue_qty' => null,
            'issue_office' => null,
            'balance_qty' => $runningBalance,
            'days_to_consume' => null,
            'transaction_id' => null,
        ];

        $hasBeginningBalance = true;

        // Filter transactions for selected year
        $yearTransactions = $transactions->filter(function ($txn) use ($yearStartDate, $yearEndDate) {
            return $txn->transaction_date >= $yearStartDate && $txn->transaction_date <= $yearEndDate;
        });

        // Process each transaction for the selected year
        foreach ($yearTransactions as $transaction) {
            // Update running balance based on transaction type
            if ($transaction->transaction_type == 'receipt') {
                $receiptQty = $transaction->quantity;
                $issueQty = null;
                $issueOffice = null;
                $runningBalance += $transaction->quantity;
            } elseif ($transaction->transaction_type == 'issue') {
                $receiptQty = null;
                $issueQty = $transaction->quantity;
                $issueOffice = $transaction->department->name ?? 'N/A';
                $runningBalance -= $transaction->quantity;
            } else {
                // For adjustments, just use the balance from the transaction
                $receiptQty = null;
                $issueQty = null;
                $issueOffice = null;
                $runningBalance = $transaction->balance_quantity;
            }

            $entries[] = [
                'date' => $transaction->transaction_date->format('Y-m-d'),
                'reference' => $transaction->reference_no,
                'receipt_qty' => $receiptQty,
                'issue_qty' => $issueQty,
                'issue_office' => $issueOffice,
                'balance_qty' => $runningBalance,
                'days_to_consume' => $transaction->transaction_type == 'receipt' ?
                    ($transaction->days_to_consume ?? null) : null,
                'transaction_id' => $transaction->transaction_id,
            ];
        }

        return $entries;
    }

    /**
     * Export stock card to PDF
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

        // Prepare the stock card data
        $stockCardEntries = $this->prepareStockCardEntries($transactions, $fundCluster, $selectedYear);

        // Generate PDF using your preferred library (e.g., dompdf, barryvdh/laravel-dompdf)
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('stock-cards.pdf', compact(
            'supply',
            'stockCardEntries',
            'fundCluster',
            'selectedYear'
        ));

        // Return the PDF for download
        return $pdf->download("stock-card-{$supply->stock_no}-{$selectedYear}.pdf");
    }
    
}
