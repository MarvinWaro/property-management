<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\SupplyStock;
use App\Models\SupplyTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


// Add these imports at the top of your SupplyLedgerCardController.php file

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;


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

        // Get selected month (null means show all months for the year)
        $selectedMonth = $request->get('month', null);

        // Pull all transactions for this supply, ordered by:
        //   1) transaction_date ASC
        //   2) reference_no     ASC
        //   3) created_at       ASC
        $transactions = SupplyTransaction::with(['department', 'user'])
            ->where('supply_id', $supplyId)
            ->orderBy('transaction_date', 'asc')
            ->orderBy('reference_no', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        // Available fund clusters
        $fundClusters = SupplyStock::where('supply_id', $supplyId)
            ->select('fund_cluster')
            ->distinct()
            ->whereNotNull('fund_cluster')
            ->pluck('fund_cluster');

        // Available years
        $availableYears = SupplyTransaction::where('supply_id', $supplyId)
            ->selectRaw('YEAR(transaction_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        if ($availableYears->isEmpty()) {
            $availableYears = collect([Carbon::now()->year]);
        }

        // Get available months for the selected year
        $availableMonths = SupplyTransaction::where('supply_id', $supplyId)
            ->whereYear('transaction_date', $selectedYear)
            ->selectRaw('MONTH(transaction_date) as month')
            ->distinct()
            ->orderBy('month', 'asc')
            ->pluck('month')
            ->map(function ($month) {
                return [
                    'value' => $month,
                    'name' => Carbon::create()->month($month)->format('F')
                ];
            });

        // Current on-hand stock
        $currentStock = SupplyStock::where('supply_id', $supplyId)
            ->where('fund_cluster', $fundCluster)
            ->sum('quantity_on_hand');

        // Average unit cost for moving‐average calculation
        $averageUnitCost = SupplyStock::where('supply_id', $supplyId)
            ->where('fund_cluster', $fundCluster)
            ->where('quantity_on_hand', '>', 0)
            ->avg('unit_cost') ?? 0;

        // Build your ledger rows
        $ledgerCardEntries = $this->prepareLedgerCardEntries(
            $transactions,
            $fundCluster,
            $averageUnitCost,
            $selectedYear,
            $selectedMonth
        );

        // Current moving‐average cost is the last row's balance_unit_cost
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
            'availableMonths',
            'selectedMonth',
            'movingAverageCost'
        ));
    }

    /**
     * Prepare ledger card entries with running balance
     */
    private function prepareLedgerCardEntries($transactions, $fundCluster, $averageUnitCost, $selectedYear, $selectedMonth = null)
    {
        $entries = [];
        $runningBalance = 0;
        $runningTotalCost = 0;
        $weightedAverageUnitCost = 0;

        // Determine the date range based on whether a month is selected
        if ($selectedMonth) {
            // If month is selected, show only that month
            $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfDay();
            $endDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->endOfMonth()->endOfDay();
        } else {
            // If no month selected, show entire year
            $startDate = Carbon::createFromDate($selectedYear, 1, 1)->startOfDay();
            $endDate = Carbon::createFromDate($selectedYear, 12, 31)->endOfDay();
        }

        // Calculate beginning balance from all transactions before the start date
        $prevTransactions = $transactions->filter(function ($txn) use ($startDate) {
            return $txn->transaction_date < $startDate;
        });

        foreach ($prevTransactions as $txn) {
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

        // --- UPDATED BEGINNING BALANCE DATE LOGIC ---
        if ($selectedMonth) {
            // For monthly, use the last day of the previous month
            $beginningBalanceDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->subDay()->format('Y-m-d');
        } else {
            // For yearly, use December 31 of previous year
            $beginningBalanceDate = Carbon::createFromDate($selectedYear, 1, 1)->subDay()->format('Y-m-d');
        }
        // --------------------------------------------

        $entries[] = [
            'date'               => $beginningBalanceDate,
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

        // Filter transactions for selected period
        $periodTransactions = $transactions->filter(function ($txn) use ($startDate, $endDate) {
            return $txn->transaction_date >= $startDate && $txn->transaction_date <= $endDate;
        });

        // Process each transaction for the selected period
        foreach ($periodTransactions as $transaction) {
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


    /**
     * Export ledger card to Excel
     */
    public function exportExcel(Request $request, $supplyId)
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

        $entityName = 'COMMISSION ON HIGHER EDUCATION REGIONAL OFFICE XII';

        // Create Excel file
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set page setup
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_LETTER);
        $sheet->getPageMargins()->setTop(0.5)->setRight(0.5)->setLeft(0.5)->setBottom(0.5);

        // Appendix 57 (top right)
        $sheet->setCellValue('L1', 'Appendix 57');
        $sheet->getStyle('L1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('L1')->getFont()->setItalic(true)->setSize(15);

        // Title
        $sheet->mergeCells('A3:L3');
        $sheet->setCellValue('A3', 'SUPPLIES LEDGER CARD');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Entity info section (without borders, with underlines)
        $currentRow = 5;

        // Entity Name and Fund Cluster row (no borders, with underlines)
        $sheet->setCellValue("A{$currentRow}", "Entity Name:");
        $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true);

        $sheet->mergeCells("B{$currentRow}:G{$currentRow}");
        $sheet->setCellValue("B{$currentRow}", strtoupper($entityName));
        $sheet->getStyle("B{$currentRow}:G{$currentRow}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("B{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $sheet->setCellValue("I{$currentRow}", "Fund Cluster:");
        $sheet->getStyle("I{$currentRow}")->getFont()->setBold(true);

        $sheet->mergeCells("J{$currentRow}:L{$currentRow}");
        $sheet->setCellValue("J{$currentRow}", $fundCluster);
        $sheet->getStyle("J{$currentRow}:L{$currentRow}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("J{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $currentRow++;

        $currentRow++; // Add space between entity info and item table

        // Item information table (with borders)
        // First row - Item and Item Code
        $sheet->setCellValue("A{$currentRow}", "Item:");
        $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true);
        $sheet->mergeCells("B{$currentRow}:G{$currentRow}");
        $sheet->setCellValue("B{$currentRow}", $supply->item_name);
        $sheet->getStyle("A{$currentRow}:G{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $sheet->setCellValue("H{$currentRow}", "Item Code:");
        $sheet->getStyle("H{$currentRow}")->getFont()->setBold(true);
        $sheet->mergeCells("I{$currentRow}:L{$currentRow}");
        $sheet->setCellValue("I{$currentRow}", $supply->stock_no);
        $sheet->getStyle("H{$currentRow}:L{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $currentRow++;

        // Second row - Description and Re-order Point
        $sheet->setCellValue("A{$currentRow}", "Description:");
        $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true);
        $sheet->mergeCells("B{$currentRow}:G{$currentRow}");
        $sheet->setCellValue("B{$currentRow}", $supply->description);
        $sheet->getStyle("A{$currentRow}:G{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $sheet->setCellValue("H{$currentRow}", "Re-order Point:");
        $sheet->getStyle("H{$currentRow}")->getFont()->setBold(true);
        $sheet->mergeCells("I{$currentRow}:L{$currentRow}");
        $sheet->setCellValue("I{$currentRow}", $supply->reorder_point);
        $sheet->getStyle("H{$currentRow}:L{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $currentRow++;

        // Third row - Unit of Measurement (spans full width)
        $sheet->setCellValue("A{$currentRow}", "Unit of Measurement:");
        $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true);
        $sheet->mergeCells("B{$currentRow}:L{$currentRow}");
        $sheet->setCellValue("B{$currentRow}", $supply->unit_of_measurement);
        $sheet->getStyle("A{$currentRow}:L{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $currentRow++; // FIXED: Remove gap - directly to table headers (was $currentRow += 2)

        // Main ledger table headers
        $headers = [
            'A' => "Date",
            'B' => "Reference",
            'C' => "Qty.",
            'D' => "Unit Cost",
            'E' => "Total Cost",
            'F' => "Qty.",
            'G' => "Unit Cost",
            'H' => "Total Cost",
            'I' => "Qty.",
            'J' => "Unit Cost",
            'K' => "Total Cost",
            'L' => "No. of Days\nto Consume"
        ];

        // First header row with merged cells
        $sheet->setCellValue("A{$currentRow}", "Date");
        $sheet->setCellValue("B{$currentRow}", "Reference");
        $sheet->mergeCells("C{$currentRow}:E{$currentRow}");
        $sheet->setCellValue("C{$currentRow}", "Receipt");
        $sheet->mergeCells("F{$currentRow}:H{$currentRow}");
        $sheet->setCellValue("F{$currentRow}", "Issue");
        $sheet->mergeCells("I{$currentRow}:K{$currentRow}");
        $sheet->setCellValue("I{$currentRow}", "Balance");
        $sheet->setCellValue("L{$currentRow}", "No. of Days\nto Consume");

        // Style first header row
        $headerRange = "A{$currentRow}:L{$currentRow}";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'size' => 10],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'F0F0F0']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);

        $currentRow++;

        // Second header row with sub-headers
        $subHeaders = [
            'A' => "",  // Date spans both rows
            'B' => "",  // Reference spans both rows
            'C' => "Qty.",
            'D' => "Unit Cost",
            'E' => "Total Cost",
            'F' => "Qty.",
            'G' => "Unit Cost",
            'H' => "Total Cost",
            'I' => "Qty.",
            'J' => "Unit Cost",
            'K' => "Total Cost",
            'L' => ""   // Days to consume spans both rows
        ];

        foreach ($subHeaders as $col => $header) {
            if ($header !== "") {
                $sheet->setCellValue("{$col}{$currentRow}", $header);
            }
        }

        // Merge cells that span both header rows
        $sheet->mergeCells("A" . ($currentRow - 1) . ":A{$currentRow}");
        $sheet->mergeCells("B" . ($currentRow - 1) . ":B{$currentRow}");
        $sheet->mergeCells("L" . ($currentRow - 1) . ":L{$currentRow}");

        // Style second header row
        $subHeaderRange = "A{$currentRow}:L{$currentRow}";
        $sheet->getStyle($subHeaderRange)->applyFromArray([
            'font' => ['bold' => true, 'size' => 10],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'F0F0F0']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);

        $sheet->getRowDimension($currentRow - 1)->setRowHeight(25);
        $sheet->getRowDimension($currentRow)->setRowHeight(25);

        // Data rows
        $currentRow++;

        foreach ($ledgerCardEntries as $entry) {
            $sheet->setCellValue("A{$currentRow}", Carbon::parse($entry['date'])->format('m/d/Y'));
            $sheet->setCellValue("B{$currentRow}", $entry['reference']);

            // Receipt columns
            $sheet->setCellValue("C{$currentRow}", $entry['receipt_qty'] ? $entry['receipt_qty'] : '');
            $sheet->setCellValue("D{$currentRow}", $entry['receipt_unit_cost'] ? $entry['receipt_unit_cost'] : '');
            $sheet->setCellValue("E{$currentRow}", $entry['receipt_total_cost'] ? $entry['receipt_total_cost'] : '');

            // Issue columns
            $sheet->setCellValue("F{$currentRow}", $entry['issue_qty'] ? $entry['issue_qty'] : '');
            $sheet->setCellValue("G{$currentRow}", $entry['issue_unit_cost'] ? $entry['issue_unit_cost'] : '');
            $sheet->setCellValue("H{$currentRow}", $entry['issue_total_cost'] ? $entry['issue_total_cost'] : '');

            // Balance columns
            $sheet->setCellValue("I{$currentRow}", $entry['balance_qty']);
            $sheet->setCellValue("J{$currentRow}", $entry['balance_unit_cost']);
            $sheet->setCellValue("K{$currentRow}", $entry['balance_total_cost']);

            // Days to consume
            $sheet->setCellValue("L{$currentRow}", $entry['days_to_consume'] ?: '');

            // Format numbers
            $sheet->getStyle("C{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("D{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle("E{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle("F{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("G{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle("H{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle("I{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("J{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle("K{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.00');

            // Apply borders
            $sheet->getStyle("A{$currentRow}:L{$currentRow}")->getBorders()
                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Alignment
            $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("C{$currentRow}:L{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$currentRow}:E{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("G{$currentRow}:H{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("J{$currentRow}:K{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $currentRow++;
        }

        // Add empty rows to match template format (minimum 15 rows)
        $emptyRowsToAdd = max(15 - count($ledgerCardEntries), 0);
        for ($i = 0; $i < $emptyRowsToAdd; $i++) {
            $sheet->getStyle("A{$currentRow}:L{$currentRow}")->getBorders()
                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $currentRow++;
        }

        // Adjust column widths
        $sheet->getColumnDimension('A')->setWidth(12);  // Date
        $sheet->getColumnDimension('B')->setWidth(15);  // Reference
        $sheet->getColumnDimension('C')->setWidth(8);   // Receipt Qty
        $sheet->getColumnDimension('D')->setWidth(12);  // Receipt Unit Cost
        $sheet->getColumnDimension('E')->setWidth(12);  // Receipt Total Cost
        $sheet->getColumnDimension('F')->setWidth(8);   // Issue Qty
        $sheet->getColumnDimension('G')->setWidth(12);  // Issue Unit Cost
        $sheet->getColumnDimension('H')->setWidth(12);  // Issue Total Cost
        $sheet->getColumnDimension('I')->setWidth(8);   // Balance Qty
        $sheet->getColumnDimension('J')->setWidth(12);  // Balance Unit Cost
        $sheet->getColumnDimension('K')->setWidth(12);  // Balance Total Cost
        $sheet->getColumnDimension('L')->setWidth(12);  // Days to Consume

        // Write file
        $writer = new Xlsx($spreadsheet);
        $filename = 'Supply_Ledger_Card_' . $supply->stock_no . '_' . $selectedYear . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

}
