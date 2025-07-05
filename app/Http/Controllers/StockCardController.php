<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\SupplyStock;
use App\Models\SupplyTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Add these imports at the top of your StockCardController.php file

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

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

        // Get selected month (null means show all months for the year)
        $selectedMonth = $request->get('month', null);

        // Get all transactions for this supply
        $transactions = SupplyTransaction::with(['department', 'user'])
            ->where('supply_id', $supplyId)
            ->orderBy('transaction_date', 'asc')
            ->orderBy('reference_no', 'asc')
            ->orderBy('created_at', 'asc')
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

        // Calculate current stock
        $currentStock = SupplyStock::where('supply_id', $supplyId)
            ->where('fund_cluster', $fundCluster)
            ->sum('quantity_on_hand');

        // Prepare the stock card data
        $stockCardEntries = $this->prepareStockCardEntries(
            $transactions,
            $fundCluster,
            $selectedYear,
            $selectedMonth
        );

        return view('stock-cards.show', compact(
            'supply',
            'stockCardEntries',
            'fundClusters',
            'fundCluster',
            'currentStock',
            'availableYears',
            'selectedYear',
            'availableMonths',
            'selectedMonth'
        ));
    }

    private function prepareStockCardEntries($transactions, $fundCluster, $selectedYear, $selectedMonth = null)
    {
        $entries = [];
        $runningBalance = 0;
        $hasBeginningBalance = false;

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
            } elseif ($txn->transaction_type == 'issue') {
                $runningBalance -= $txn->quantity;
            }
        }

        // Add beginning balance entry
        $beginningBalanceDate = $selectedMonth
            ? Carbon::createFromDate($selectedYear, $selectedMonth, 1)->format('Y-m-d')
            : $startDate->format('Y-m-d');

        $entries[] = [
            'date' => $beginningBalanceDate,
            'reference' => 'Beginning Balance',
            'receipt_qty' => null,
            'issue_qty' => null,
            'issue_office' => null,
            'balance_qty' => $runningBalance,
            'days_to_consume' => null,
            'transaction_id' => null,
        ];

        $hasBeginningBalance = true;

        // Filter transactions for selected period
        $periodTransactions = $transactions->filter(function ($txn) use ($startDate, $endDate) {
            return $txn->transaction_date >= $startDate && $txn->transaction_date <= $endDate;
        });

        // Process each transaction for the selected period
        foreach ($periodTransactions as $transaction) {
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


    /**
     * Export stock card to Excel
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

        // Prepare the stock card data
        $stockCardEntries = $this->prepareStockCardEntries($transactions, $fundCluster, $selectedYear);

        $entityName = 'COMMISSION ON HIGHER EDUCATION REGIONAL OFFICE XII';

        // Create Excel file
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set page setup
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_LETTER);
        $sheet->getPageMargins()->setTop(0.5)->setRight(0.5)->setLeft(0.5)->setBottom(0.5);

        // Appendix 58 (top right) - Fixed to column G
        $sheet->setCellValue('G1', 'Appendix 58');
        $sheet->getStyle('G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('G1')->getFont()->setItalic(true)->setSize(15);

        // Title - Fixed to span A-G
        $sheet->mergeCells('A3:G3');
        $sheet->setCellValue('A3', 'STOCK CARD');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Entity info section (following template layout)
        $currentRow = 5;

        // FIXED: Entity Name (A-E) and Fund Cluster (F-G) - no borders, just underlines
        $sheet->setCellValue("A{$currentRow}", "Entity Name:");
        $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true);

        $sheet->mergeCells("B{$currentRow}:E{$currentRow}");
        $sheet->setCellValue("B{$currentRow}", strtoupper($entityName));
        $sheet->getStyle("B{$currentRow}:E{$currentRow}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("B{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $sheet->setCellValue("F{$currentRow}", "Fund Cluster:");
        $sheet->getStyle("F{$currentRow}")->getFont()->setBold(true);

        $sheet->setCellValue("G{$currentRow}", $fundCluster);
        $sheet->getStyle("G{$currentRow}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("G{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $currentRow++; // Move to next row for item info table

        // FIXED: Item information table with borders (following template layout)
        // First row - Item (A-E) and Stock No. (F-G)
        $sheet->setCellValue("A{$currentRow}", "Item:");
        $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true);
        $sheet->mergeCells("B{$currentRow}:E{$currentRow}");
        $sheet->setCellValue("B{$currentRow}", $supply->item_name);
        $sheet->getStyle("A{$currentRow}:E{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $sheet->setCellValue("F{$currentRow}", "Stock No.:");
        $sheet->getStyle("F{$currentRow}")->getFont()->setBold(true);
        $sheet->setCellValue("G{$currentRow}", $supply->stock_no);
        $sheet->getStyle("F{$currentRow}:G{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $currentRow++;

        // Second row - Description (A-E) and Re-order Point (F-G)
        $sheet->setCellValue("A{$currentRow}", "Description:");
        $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true);
        $sheet->mergeCells("B{$currentRow}:E{$currentRow}");
        $sheet->setCellValue("B{$currentRow}", $supply->description);
        $sheet->getStyle("A{$currentRow}:E{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $sheet->setCellValue("F{$currentRow}", "Re-order Point:");
        $sheet->getStyle("F{$currentRow}")->getFont()->setBold(true);
        $sheet->setCellValue("G{$currentRow}", $supply->reorder_point);
        $sheet->getStyle("F{$currentRow}:G{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $currentRow++;

        // Third row - Unit of Measurement (spans A-G)
        $sheet->setCellValue("A{$currentRow}", "Unit of Measurement:");
        $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true);
        $sheet->mergeCells("B{$currentRow}:G{$currentRow}");
        $sheet->setCellValue("B{$currentRow}", $supply->unit_of_measurement);
        $sheet->getStyle("A{$currentRow}:G{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $currentRow++; // NO GAP - directly to table headers

        // FIXED: Main stock card table headers (A-G only, following template)
        // First header row with merged cells
        $sheet->setCellValue("A{$currentRow}", "Date");
        $sheet->setCellValue("B{$currentRow}", "Reference");
        $sheet->setCellValue("C{$currentRow}", "Receipt");
        $sheet->mergeCells("D{$currentRow}:E{$currentRow}");
        $sheet->setCellValue("D{$currentRow}", "Issue");
        $sheet->setCellValue("F{$currentRow}", "Balance");
        $sheet->setCellValue("G{$currentRow}", "No. of Days\nto Consume");

        // Style first header row
        $headerRange = "A{$currentRow}:G{$currentRow}";
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
            'A' => "",      // Date spans both rows
            'B' => "",      // Reference spans both rows
            'C' => "Qty.",  // Receipt quantity only
            'D' => "Qty.",  // Issue quantity
            'E' => "Office", // Issue office
            'F' => "Qty.",  // Balance quantity
            'G' => ""       // Days to consume spans both rows
        ];

        foreach ($subHeaders as $col => $header) {
            if ($header !== "") {
                $sheet->setCellValue("{$col}{$currentRow}", $header);
            }
        }

        // Merge cells that span both header rows
        $sheet->mergeCells("A" . ($currentRow - 1) . ":A{$currentRow}");
        $sheet->mergeCells("B" . ($currentRow - 1) . ":B{$currentRow}");
        $sheet->mergeCells("F" . ($currentRow - 1) . ":F{$currentRow}");
        $sheet->mergeCells("G" . ($currentRow - 1) . ":G{$currentRow}");

        // Style second header row
        $subHeaderRange = "A{$currentRow}:G{$currentRow}";
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

        foreach ($stockCardEntries as $entry) {
            $sheet->setCellValue("A{$currentRow}", Carbon::parse($entry['date'])->format('m/d/Y'));
            $sheet->setCellValue("B{$currentRow}", $entry['reference']);

            // Receipt column (C only)
            $sheet->setCellValue("C{$currentRow}", $entry['receipt_qty'] ? $entry['receipt_qty'] : '');

            // Issue columns (D-E)
            $sheet->setCellValue("D{$currentRow}", $entry['issue_qty'] ? $entry['issue_qty'] : '');
            $sheet->setCellValue("E{$currentRow}", $entry['issue_office'] ? $entry['issue_office'] : '');

            // Balance (F)
            $sheet->setCellValue("F{$currentRow}", $entry['balance_qty']);

            // Days to consume (G)
            $sheet->setCellValue("G{$currentRow}", $entry['days_to_consume'] ?: '');

            // Format numbers
            $sheet->getStyle("C{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("D{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("F{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');

            // Apply borders
            $sheet->getStyle("A{$currentRow}:G{$currentRow}")->getBorders()
                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Alignment
            $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("C{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("F{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("G{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $currentRow++;
        }

        // Add empty rows to match template format (minimum 15 rows)
        $emptyRowsToAdd = max(15 - count($stockCardEntries), 0);
        for ($i = 0; $i < $emptyRowsToAdd; $i++) {
            $sheet->getStyle("A{$currentRow}:G{$currentRow}")->getBorders()
                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $currentRow++;
        }

        // Adjust column widths for A-G layout
        $sheet->getColumnDimension('A')->setWidth(12);  // Date
        $sheet->getColumnDimension('B')->setWidth(18);  // Reference
        $sheet->getColumnDimension('C')->setWidth(10);  // Receipt Qty
        $sheet->getColumnDimension('D')->setWidth(10);  // Issue Qty
        $sheet->getColumnDimension('E')->setWidth(25);  // Issue Office (wider)
        $sheet->getColumnDimension('F')->setWidth(12);  // Balance
        $sheet->getColumnDimension('G')->setWidth(15);  // Days to Consume

        // Write file
        $writer = new Xlsx($spreadsheet);
        $filename = 'Stock_Card_' . $supply->stock_no . '_' . $selectedYear . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

}
