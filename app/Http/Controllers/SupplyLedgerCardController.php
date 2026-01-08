<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\SupplyStock;
use App\Models\SupplyTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;

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
        $fundCluster = $request->get('fund_cluster', '101');

        // Available years - get this FIRST to determine proper defaults
        $availableYears = SupplyTransaction::where('supply_id', $supplyId)
            ->selectRaw('YEAR(transaction_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        if ($availableYears->isEmpty()) {
            $availableYears = collect([Carbon::now()->year]);
        }

        // Get selected year - if not specified, default to the LATEST year with transactions
        $selectedYear = $request->get('year');
        if ($selectedYear === null) {
            $selectedYear = $availableYears->first() ?? Carbon::now()->year;
        }
        $selectedYear = (int) $selectedYear;

        // Get selected month (null means show all months for the year)
        $selectedMonth = $request->get('month', null);
        if ($selectedMonth !== null) {
            $selectedMonth = (int) $selectedMonth;
        }

        // Pull all transactions for this supply
        $transactions = SupplyTransaction::with(['department', 'user'])
            ->where('supply_id', $supplyId)
            ->orderBy('transaction_date')
            ->orderBy('created_at')
            ->get();

        // Available fund clusters
        $fundClusters = SupplyStock::where('supply_id', $supplyId)
            ->select('fund_cluster')
            ->distinct()
            ->whereNotNull('fund_cluster')
            ->pluck('fund_cluster');

        // Get available months for the selected year
        $availableMonths = SupplyTransaction::where('supply_id', $supplyId)
            ->whereYear('transaction_date', $selectedYear)
            ->selectRaw('MONTH(transaction_date) as month')
            ->distinct()
            ->orderBy('month', 'asc')
            ->pluck('month')
            ->map(function ($month) {
                $monthInt = (int) $month;
                $monthName = DateTime::createFromFormat('!m', $monthInt)->format('F');

                return [
                    'value' => $monthInt,
                    'name' => $monthName
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
                $runningTotalCost += $txn->total_cost;
            } elseif ($txn->transaction_type == 'issue') {
                if ($runningBalance > 0) {
                    $costToDeduct = ($runningTotalCost / $runningBalance) * $txn->quantity;
                    $runningTotalCost = max(0, $runningTotalCost - $costToDeduct);
                }
                $runningBalance -= $txn->quantity;
            }
        }

        // Calculate weighted average for beginning balance
        $weightedAverageUnitCost = $runningBalance > 0 ? $runningTotalCost / $runningBalance : 0;

        // Set beginning balance date
        if ($selectedMonth) {
            $beginningBalanceDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->subDay()->format('Y-m-d');
        } else {
            $beginningBalanceDate = Carbon::createFromDate($selectedYear, 1, 1)->subDay()->format('Y-m-d');
        }

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

                $weightedAverageUnitCost = $runningBalance > 0
                    ? $runningTotalCost / $runningBalance
                    : 0;

                $issueUnitCost  = $weightedAverageUnitCost;
                $issueTotalCost = $issueQty * $issueUnitCost;

                $runningBalance   -= $issueQty;
                $runningTotalCost = max(0, $runningTotalCost - $issueTotalCost);

            } else {
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
        $selectedMonth = $request->get('month', null);

        $transactions = SupplyTransaction::with(['department', 'user'])
            ->where('supply_id', $supplyId)
            ->orderBy('transaction_date')
            ->orderBy('created_at')
            ->get();

        $averageUnitCost = SupplyStock::where('supply_id', $supplyId)
            ->where('fund_cluster', $fundCluster)
            ->where('quantity_on_hand', '>', 0)
            ->avg('unit_cost') ?? 0;

        $ledgerCardEntries = $this->prepareLedgerCardEntries($transactions, $fundCluster, $averageUnitCost, $selectedYear, $selectedMonth);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('supply-ledger-cards.pdf', compact(
            'supply',
            'ledgerCardEntries',
            'fundCluster',
            'selectedYear',
            'selectedMonth'
        ));

        $filename = "supplies-ledger-card-{$supply->stock_no}-{$selectedYear}";
        if ($selectedMonth) {
            $filename .= "-" . str_pad($selectedMonth, 2, '0', STR_PAD_LEFT);
        }
        $filename .= ".pdf";

        return $pdf->download($filename);
    }


    /**
     * Export ledger card to Excel
     */
    public function exportExcel(Request $request, $supplyId)
    {
        $supply = Supply::findOrFail($supplyId);
        $fundCluster = $request->get('fund_cluster', '101');
        $selectedYear = $request->get('year', Carbon::now()->year);
        $selectedMonth = $request->get('month', null);

        $transactions = SupplyTransaction::with(['department', 'user'])
            ->where('supply_id', $supplyId)
            ->orderBy('transaction_date')
            ->orderBy('created_at')
            ->get();

        $averageUnitCost = SupplyStock::where('supply_id', $supplyId)
            ->where('fund_cluster', $fundCluster)
            ->where('quantity_on_hand', '>', 0)
            ->avg('unit_cost') ?? 0;

        $ledgerCardEntries = $this->prepareLedgerCardEntries($transactions, $fundCluster, $averageUnitCost, $selectedYear, $selectedMonth);

        $entityName = 'COMMISSION ON HIGHER EDUCATION REGIONAL OFFICE XII';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_LETTER);
        $sheet->getPageMargins()->setTop(0.5)->setRight(0.5)->setLeft(0.5)->setBottom(0.5);

        $sheet->setCellValue('L1', 'Appendix 57');
        $sheet->getStyle('L1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('L1')->getFont()->setItalic(true)->setSize(15);

        $sheet->mergeCells('A3:L3');
        $sheet->setCellValue('A3', 'SUPPLIES LEDGER CARD');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $currentRow = 5;

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
        $currentRow++;

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

        $sheet->setCellValue("A{$currentRow}", "Unit of Measurement:");
        $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true);
        $sheet->mergeCells("B{$currentRow}:L{$currentRow}");
        $sheet->setCellValue("B{$currentRow}", $supply->unit_of_measurement);
        $sheet->getStyle("A{$currentRow}:L{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $currentRow++;

        $sheet->setCellValue("A{$currentRow}", "Date");
        $sheet->setCellValue("B{$currentRow}", "Reference");
        $sheet->mergeCells("C{$currentRow}:E{$currentRow}");
        $sheet->setCellValue("C{$currentRow}", "Receipt");
        $sheet->mergeCells("F{$currentRow}:H{$currentRow}");
        $sheet->setCellValue("F{$currentRow}", "Issue");
        $sheet->mergeCells("I{$currentRow}:K{$currentRow}");
        $sheet->setCellValue("I{$currentRow}", "Balance");
        $sheet->setCellValue("L{$currentRow}", "No. of Days\nto Consume");

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

        $subHeaders = [
            'A' => "",
            'B' => "",
            'C' => "Qty.",
            'D' => "Unit Cost",
            'E' => "Total Cost",
            'F' => "Qty.",
            'G' => "Unit Cost",
            'H' => "Total Cost",
            'I' => "Qty.",
            'J' => "Unit Cost",
            'K' => "Total Cost",
            'L' => ""
        ];

        foreach ($subHeaders as $col => $header) {
            if ($header !== "") {
                $sheet->setCellValue("{$col}{$currentRow}", $header);
            }
        }

        $sheet->mergeCells("A" . ($currentRow - 1) . ":A{$currentRow}");
        $sheet->mergeCells("B" . ($currentRow - 1) . ":B{$currentRow}");
        $sheet->mergeCells("L" . ($currentRow - 1) . ":L{$currentRow}");

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

        $currentRow++;

        foreach ($ledgerCardEntries as $entry) {
            $sheet->setCellValue("A{$currentRow}", Carbon::parse($entry['date'])->format('m/d/Y'));
            $sheet->setCellValue("B{$currentRow}", $entry['reference']);

            $sheet->setCellValue("C{$currentRow}", $entry['receipt_qty'] ? $entry['receipt_qty'] : '');
            $sheet->setCellValue("D{$currentRow}", $entry['receipt_unit_cost'] ? $entry['receipt_unit_cost'] : '');
            $sheet->setCellValue("E{$currentRow}", $entry['receipt_total_cost'] ? $entry['receipt_total_cost'] : '');

            $sheet->setCellValue("F{$currentRow}", $entry['issue_qty'] ? $entry['issue_qty'] : '');
            $sheet->setCellValue("G{$currentRow}", $entry['issue_unit_cost'] ? $entry['issue_unit_cost'] : '');
            $sheet->setCellValue("H{$currentRow}", $entry['issue_total_cost'] ? $entry['issue_total_cost'] : '');

            $sheet->setCellValue("I{$currentRow}", $entry['balance_qty']);
            $sheet->setCellValue("J{$currentRow}", $entry['balance_unit_cost']);
            $sheet->setCellValue("K{$currentRow}", $entry['balance_total_cost']);

            $sheet->setCellValue("L{$currentRow}", $entry['days_to_consume'] ?: '');

            $sheet->getStyle("C{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("D{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.0000');
            $sheet->getStyle("E{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.0000');
            $sheet->getStyle("F{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("G{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.0000');
            $sheet->getStyle("H{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.0000');
            $sheet->getStyle("I{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("J{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.0000');
            $sheet->getStyle("K{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.0000');

            $sheet->getStyle("A{$currentRow}:L{$currentRow}")->getBorders()
                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("C{$currentRow}:L{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$currentRow}:E{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("G{$currentRow}:H{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("J{$currentRow}:K{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $currentRow++;
        }

        $emptyRowsToAdd = max(15 - count($ledgerCardEntries), 0);
        for ($i = 0; $i < $emptyRowsToAdd; $i++) {
            $sheet->getStyle("A{$currentRow}:L{$currentRow}")->getBorders()
                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $currentRow++;
        }

        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(8);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(8);
        $sheet->getColumnDimension('G')->setWidth(12);
        $sheet->getColumnDimension('H')->setWidth(12);
        $sheet->getColumnDimension('I')->setWidth(8);
        $sheet->getColumnDimension('J')->setWidth(12);
        $sheet->getColumnDimension('K')->setWidth(12);
        $sheet->getColumnDimension('L')->setWidth(12);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Supply_Ledger_Card_' . $supply->stock_no . '_' . $selectedYear;
        if ($selectedMonth) {
            $filename .= '_' . str_pad($selectedMonth, 2, '0', STR_PAD_LEFT);
        }
        $filename .= '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

}
