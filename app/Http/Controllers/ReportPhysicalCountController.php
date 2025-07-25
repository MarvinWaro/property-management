<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\SupplyStock;
use App\Models\SupplyTransaction;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// PhpSpreadsheet imports
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\RichText\Run;

class ReportPhysicalCountController extends Controller
{
    public function index(Request $request)
    {
        $selectedYear = $request->get('year', Carbon::now()->year);
        $selectedSemester = $request->get('semester', $this->getCurrentSemester());
        $selectedFundCluster = $request->get('fund_cluster'); // No default - will be null for "All Fund Clusters"

        // Get available years from supply transactions
        $availableYears = SupplyTransaction::selectRaw('YEAR(transaction_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        if ($availableYears->isEmpty()) {
            $availableYears = collect([Carbon::now()->year]);
        }

        // Get departments
        $departments = Department::orderBy('name')->get();

        // Get fund clusters from supply stocks
        $fundClusters = SupplyStock::distinct()
            ->whereNotNull('fund_cluster')
            ->pluck('fund_cluster')
            ->sort()
            ->values();

        // If no fund clusters found, provide defaults
        if ($fundClusters->isEmpty()) {
            $fundClusters = collect(['101', '151']);
        }

        // Log for debugging
        \Log::info('RPCI Index Loaded', [
            'available_years_count' => $availableYears->count(),
            'fund_clusters_count' => $fundClusters->count(),
            'selected_year' => $selectedYear,
            'selected_semester' => $selectedSemester,
            'selected_fund_cluster' => $selectedFundCluster ?? 'All'
        ]);

        return view('rpci.index', compact(
            'availableYears',
            'departments',
            'fundClusters',
            'selectedYear',
            'selectedSemester',
            'selectedFundCluster'
        ));
    }

    public function generate(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'year' => 'required|integer',
            'semester' => 'required|in:1,2',
            'fund_cluster' => 'nullable|in:101,151',
            'department_id' => 'nullable|exists:departments,id'
        ]);

        $year = $validated['year'];
        $semester = $validated['semester'];
        $departmentId = $validated['department_id'] ?? null;
        $fundCluster = $validated['fund_cluster'] ?? null; // null = All Fund Clusters

        // Parse semester dates
        $semesterDates = $this->parseSemester($year, $semester);
        $startDate = $semesterDates['start'];
        $endDate = $semesterDates['end'];

        // Get filter data for the inline filters
        $availableYears = SupplyTransaction::selectRaw('YEAR(transaction_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Get fund clusters
        $fundClusters = SupplyStock::distinct()
            ->whereNotNull('fund_cluster')
            ->pluck('fund_cluster')
            ->sort()
            ->values();

        if ($fundClusters->isEmpty()) {
            $fundClusters = collect(['101', '151']);
        }

        // Get supplies with their stock data
        $suppliesQuery = Supply::with(['category', 'stocks'])
            ->whereHas('stocks', function($query) use ($fundCluster) {
                if ($fundCluster) {
                    $query->where('fund_cluster', $fundCluster);
                }
            });

        $supplies = $suppliesQuery->get();

        // Build report data
        $reportData = collect();

        foreach ($supplies as $supply) {
            foreach ($supply->stocks as $stock) {
                // Skip if fund cluster filter doesn't match
                if ($fundCluster && $stock->fund_cluster !== $fundCluster) {
                    continue;
                }

                // Calculate book quantity (stock card quantity) at the end of semester
                $bookQuantity = $this->calculateBookQuantity($stock, $endDate);

                // For now, we'll use the current quantity as physical count
                // In a real system, this would come from actual physical count data
                $physicalCount = $stock->quantity_on_hand;

                // Calculate variance
                $variance = $physicalCount - $bookQuantity;
                $varianceAmount = $variance * $stock->unit_cost;

                // Only include items that have activity or discrepancies
                if ($bookQuantity > 0 || $physicalCount > 0 || $variance != 0) {
                    $reportData->push([
                        'supply_id' => $supply->supply_id,
                        'stock_no' => $supply->stock_no,
                        'item_name' => $supply->item_name,
                        'description' => $supply->description,
                        'unit' => $supply->unit_of_measurement,
                        'fund_cluster' => $stock->fund_cluster,
                        'book_quantity' => $bookQuantity,
                        'physical_count' => $physicalCount,
                        'variance_quantity' => $variance,
                        'unit_cost' => $stock->unit_cost,
                        'variance_amount' => $varianceAmount,
                        'remarks' => $this->generateVarianceRemarks($variance),
                    ]);
                }
            }
        }

        // Sort by stock number
        $reportData = $reportData->sortBy('stock_no')->values();

        // Calculate summary
        $summary = [
            'total_items' => $reportData->count(),
            'total_book_value' => $reportData->sum(function($item) {
                return $item['book_quantity'] * $item['unit_cost'];
            }),
            'total_physical_value' => $reportData->sum(function($item) {
                return $item['physical_count'] * $item['unit_cost'];
            }),
            'total_variance_amount' => $reportData->sum('variance_amount'),
            'items_with_shortage' => $reportData->where('variance_quantity', '<', 0)->count(),
            'items_with_overage' => $reportData->where('variance_quantity', '>', 0)->count(),
            'items_balanced' => $reportData->where('variance_quantity', '=', 0)->count(),
        ];

        // Get entity information
        $entityName = 'COMMISSION ON HIGHER EDUCATION REGIONAL OFFICE XII';

        // Log for debugging
        \Log::info('RPCI Report Generated', [
            'year' => $year,
            'semester' => $semester,
            'fund_cluster' => $fundCluster ?? 'All',
            'department_id' => $departmentId,
            'total_items' => $reportData->count(),
            'total_variance' => $summary['total_variance_amount']
        ]);

        return view('rpci.report', compact(
            'reportData',
            'summary',
            'year',
            'semester',
            'startDate',
            'endDate',
            'entityName',
            'fundCluster',
            'departmentId',
            'availableYears',
            'fundClusters'
        ));
    }

/**
     * Calculate weighted average unit cost based on transactions (same as Supply Ledger Card)
     */
    private function calculateWeightedAverageUnitCost($stock, $endDate)
    {
        // Get all transactions for this supply and fund cluster up to the end date
        $transactions = SupplyTransaction::where('supply_id', $stock->supply_id)
            ->where('fund_cluster', $stock->fund_cluster)
            ->where('transaction_date', '<=', $endDate)
            ->orderBy('transaction_date', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        $runningBalance = 0;
        $runningTotalCost = 0;

        // Process each transaction to calculate weighted average (same logic as Supply Ledger Card)
        foreach ($transactions as $transaction) {
            if ($transaction->transaction_type == 'receipt') {
                // Add to running totals
                $runningBalance += $transaction->quantity;
                $runningTotalCost += $transaction->total_cost;
            } elseif ($transaction->transaction_type == 'issue') {
                // Calculate cost to deduct based on current weighted average
                if ($runningBalance > 0) {
                    $currentWeightedAverage = $runningTotalCost / $runningBalance;
                    $costToDeduct = $currentWeightedAverage * $transaction->quantity;
                    $runningTotalCost = max(0, $runningTotalCost - $costToDeduct);
                }
                $runningBalance -= $transaction->quantity;
            } elseif ($transaction->transaction_type == 'adjustment') {
                // For adjustments, use the provided values
                if ($transaction->balance_quantity !== null && $transaction->unit_cost !== null) {
                    $runningBalance = $transaction->balance_quantity;
                    $runningTotalCost = $runningBalance * $transaction->unit_cost;
                }
            }
        }

        // Return weighted average unit cost
        return $runningBalance > 0 ? ($runningTotalCost / $runningBalance) : 0;
    }

    public function exportExcel(Request $request)
    {
        // Validate inputs
        $validated = $request->validate([
            'year' => 'required|integer',
            'semester' => 'required|in:1,2',
            'fund_cluster' => 'nullable|in:101,151',
        ]);

        $year = $validated['year'];
        $semester = $validated['semester'];
        $fundCluster = $validated['fund_cluster'] ?? null;

        // Parse semester dates
        $semesterDates = $this->parseSemester($year, $semester);
        $endDate = $semesterDates['end'];

        // Get supplies with transactions and calculate balances
        $supplies = Supply::with(['category', 'stocks'])
            ->whereHas('stocks', function($query) use ($fundCluster) {
                if ($fundCluster) {
                    $query->where('fund_cluster', $fundCluster);
                }
            })
            ->get();

        $reportData = collect();

        foreach ($supplies as $supply) {
            foreach ($supply->stocks as $stock) {
                // Skip if fund cluster filter doesn't match
                if ($fundCluster && $stock->fund_cluster !== $fundCluster) {
                    continue;
                }

                // Calculate book quantity at semester end
                $bookQuantity = $this->calculateBookQuantity($stock, $endDate);

                // FIXED: Calculate weighted average unit cost (same as Supply Ledger Card)
                $weightedAverageUnitCost = $this->calculateWeightedAverageUnitCost($stock, $endDate);

                // Only include items with book balance
                if ($bookQuantity > 0) {
                    $reportData->push([
                        'stock_no' => $supply->stock_no,
                        'item_name' => $supply->item_name,
                        'description' => $supply->description,
                        'unit' => $supply->unit_of_measurement,
                        'unit_value' => $weightedAverageUnitCost, // FIXED: Use weighted average instead of $stock->unit_cost
                        'balance_per_card' => $bookQuantity,
                    ]);
                }
            }
        }

        // Sort by stock number
        $reportData = $reportData->sortBy('stock_no')->values();

        $entityName = 'COMMISSION ON HIGHER EDUCATION REGIONAL OFFICE XII';

        // Create Excel file
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set page setup
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_LEGAL);
        $sheet->getPageMargins()->setTop(0.5)->setRight(0.5)->setLeft(0.5)->setBottom(0.5);

        // Appendix 66 (top right) - Cell L1
        $sheet->setCellValue('L1', 'Appendix 66');
        $sheet->getStyle('L1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('L1')->getFont()->setItalic(true)->setSize(10);

        // Title - Row 3, centered across columns
        $sheet->mergeCells('A3:L3');
        $sheet->setCellValue('A3', 'REPORT ON THE PHYSICAL COUNT OF INVENTORIES');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Subtitle - Row 4, centered
        $sheet->mergeCells('A4:L4');
        $sheet->setCellValue('A4', '(Type of Inventory Item)');
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4')->getFont()->setItalic(true);

        // Underline for subtitle
        $sheet->getStyle('E4:H4')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

        // As at - Row 6
        $sheet->setCellValue('A6', 'As at');
        $sheet->mergeCells('B6:F6');
        $sheet->setCellValue('B6', $endDate->format('F d, Y'));
        $sheet->getStyle('B6:F6')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

        // Fund Cluster - Row 8
        $sheet->setCellValue('A8', 'Fund Cluster:');
        $sheet->mergeCells('B8:F8');
        $fundClusterDisplay = $fundCluster ? $fundCluster : 'All Fund Clusters';
        $sheet->setCellValue('B8', $fundClusterDisplay);
        $sheet->getStyle('B8:F8')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

        // Accountability section - Row 10
        $sheet->setCellValue('A10', 'For which');
        $sheet->mergeCells('B10:D10');
        $sheet->setCellValue('B10', '(Name of Accountable Officer)');
        $sheet->getStyle('B10:D10')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

        $sheet->setCellValue('E10', ',');

        $sheet->mergeCells('F10:G10');
        $sheet->setCellValue('F10', '(Official Designation)');
        $sheet->getStyle('F10:G10')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

        $sheet->setCellValue('H10', ',');

        $sheet->mergeCells('I10:K10');
        $sheet->setCellValue('I10', '(Entity Name)');
        $sheet->getStyle('I10:K10')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

        // Continuation of accountability - Row 11
        $sheet->setCellValue('A11', 'is accountable, having assumed such accountability on');
        $sheet->mergeCells('I11:L11');
        $sheet->setCellValue('I11', '(Date of Assumption)');
        $sheet->getStyle('I11:L11')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

        // Main table starts at Row 13
        $currentRow = 13;

        // Table headers
        $sheet->setCellValue("A{$currentRow}", 'Article');
        $sheet->setCellValue("B{$currentRow}", 'Description');
        $sheet->setCellValue("C{$currentRow}", 'Stock Number');
        $sheet->setCellValue("D{$currentRow}", 'Unit of Measure');
        $sheet->setCellValue("E{$currentRow}", 'Unit Value');

        // Multi-column headers
        $sheet->mergeCells("F{$currentRow}:F" . ($currentRow + 1));
        $sheet->setCellValue("F{$currentRow}", "Balance Per Card");

        $sheet->mergeCells("G{$currentRow}:G" . ($currentRow + 1));
        $sheet->setCellValue("G{$currentRow}", "On Hand Per Count");

        $sheet->mergeCells("H{$currentRow}:I{$currentRow}");
        $sheet->setCellValue("H{$currentRow}", "Shortage/Overage");

        $sheet->mergeCells("J{$currentRow}:J" . ($currentRow + 1));
        $sheet->setCellValue("J{$currentRow}", "Remarks");

        // Sub-headers for multi-column sections
        $currentRow++;
        $sheet->setCellValue("A{$currentRow}", '');
        $sheet->setCellValue("B{$currentRow}", '');
        $sheet->setCellValue("C{$currentRow}", '');
        $sheet->setCellValue("D{$currentRow}", '');
        $sheet->setCellValue("E{$currentRow}", '');
        $sheet->setCellValue("F{$currentRow}", '(Quantity)');
        $sheet->setCellValue("G{$currentRow}", '(Quantity)');
        $sheet->setCellValue("H{$currentRow}", 'Quantity');
        $sheet->setCellValue("I{$currentRow}", 'Value');
        $sheet->setCellValue("J{$currentRow}", '');

        // Merge cells for single-column headers that span both rows
        $sheet->mergeCells("A" . ($currentRow - 1) . ":A{$currentRow}");
        $sheet->mergeCells("B" . ($currentRow - 1) . ":B{$currentRow}");
        $sheet->mergeCells("C" . ($currentRow - 1) . ":C{$currentRow}");
        $sheet->mergeCells("D" . ($currentRow - 1) . ":D{$currentRow}");
        $sheet->mergeCells("E" . ($currentRow - 1) . ":E{$currentRow}");

        // Style headers
        $headerRange = "A" . ($currentRow - 1) . ":J{$currentRow}";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'size' => 9],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);

        // Data rows
        $currentRow++;
        $articleNumber = 1;

        foreach ($reportData as $item) {
            $sheet->setCellValue("A{$currentRow}", $articleNumber);
            $sheet->setCellValue("B{$currentRow}", $item['item_name'] . ($item['description'] ? ', ' . $item['description'] : ''));
            $sheet->setCellValue("C{$currentRow}", $item['stock_no']);
            $sheet->setCellValue("D{$currentRow}", $item['unit']);
            $sheet->setCellValue("E{$currentRow}", $item['unit_value']);
            $sheet->setCellValue("F{$currentRow}", $item['balance_per_card']);

            // Leave these blank for manual entry
            $sheet->setCellValue("G{$currentRow}", ''); // On Hand Per Count
            $sheet->setCellValue("H{$currentRow}", ''); // Shortage/Overage Quantity
            $sheet->setCellValue("I{$currentRow}", ''); // Shortage/Overage Value
            $sheet->setCellValue("J{$currentRow}", ''); // Remarks

            // Format numbers
            $sheet->getStyle("E{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle("F{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');

            // Apply borders
            $sheet->getStyle("A{$currentRow}:J{$currentRow}")->getBorders()
                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Alignment
            $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("C{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("F{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("G{$currentRow}:J{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $currentRow++;
            $articleNumber++;
        }

        // Add empty rows to make it at least 20 rows of data
        $emptyRowsToAdd = max(20 - $reportData->count(), 0);
        for ($i = 0; $i < $emptyRowsToAdd; $i++) {
            $sheet->getStyle("A{$currentRow}:J{$currentRow}")->getBorders()
                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $currentRow++;
        }

        // Signature sections - Start after a gap
        $currentRow += 2;

        // Three signature boxes side by side
        // Certified Correct by (A-D)
        $sheet->mergeCells("A{$currentRow}:D" . ($currentRow + 6));
        $sheet->setCellValue("A{$currentRow}", "Certified Correct by:\n\n\n\n\nSignature over Printed Name of\nInventory Committee Chair and\nMembers");
        $sheet->getStyle("A{$currentRow}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setWrapText(true);
        $sheet->getStyle("A{$currentRow}:D" . ($currentRow + 6))->getBorders()
            ->getOutline()->setBorderStyle(Border::BORDER_THIN);

        // Approved by (E-G)
        $sheet->mergeCells("E{$currentRow}:G" . ($currentRow + 6));
        $sheet->setCellValue("E{$currentRow}", "Approved by:\n\n\n\n\nSignature over Printed Name of Head of\nAgency/Entity or Authorized Representative");
        $sheet->getStyle("E{$currentRow}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setWrapText(true);
        $sheet->getStyle("E{$currentRow}:G" . ($currentRow + 6))->getBorders()
            ->getOutline()->setBorderStyle(Border::BORDER_THIN);

        // Verified by (H-J)
        $sheet->mergeCells("H{$currentRow}:J" . ($currentRow + 6));
        $sheet->setCellValue("H{$currentRow}", "Verified by:\n\n\n\n\nSignature over Printed Name of COA\nRepresentative");
        $sheet->getStyle("H{$currentRow}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setWrapText(true);
        $sheet->getStyle("H{$currentRow}:J" . ($currentRow + 6))->getBorders()
            ->getOutline()->setBorderStyle(Border::BORDER_THIN);

        // Adjust column widths to match template
        $sheet->getColumnDimension('A')->setWidth(8);   // Article
        $sheet->getColumnDimension('B')->setWidth(25);  // Description
        $sheet->getColumnDimension('C')->setWidth(12);  // Stock Number
        $sheet->getColumnDimension('D')->setWidth(12);  // Unit of Measure
        $sheet->getColumnDimension('E')->setWidth(10);  // Unit Value
        $sheet->getColumnDimension('F')->setWidth(12);  // Balance Per Card
        $sheet->getColumnDimension('G')->setWidth(12);  // On Hand Per Count
        $sheet->getColumnDimension('H')->setWidth(10);  // Shortage/Overage Quantity
        $sheet->getColumnDimension('I')->setWidth(10);  // Shortage/Overage Value
        $sheet->getColumnDimension('J')->setWidth(15);  // Remarks

        // Write file
        $writer = new Xlsx($spreadsheet);
        $filename = 'RPCI_' . $year . '_S' . $semester . ($fundCluster ? "_FC{$fundCluster}" : "_AllFC") . '.xlsx';

        // Log for debugging
        \Log::info('RPCI Excel Export Generated', [
            'year' => $year,
            'semester' => $semester,
            'fund_cluster' => $fundCluster ?? 'All',
            'items_count' => $reportData->count(),
            'filename' => $filename
        ]);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Get the current semester based on current date
     */
    private function getCurrentSemester()
    {
        $currentMonth = Carbon::now()->month;
        return $currentMonth <= 6 ? 1 : 2;
    }

    /**
     * Parse semester to get start and end dates
     */
    private function parseSemester($year, $semester)
    {
        if ($semester == 1) {
            // First semester: January to June
            $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
            $endDate = Carbon::createFromDate($year, 6, 30)->endOfDay();
        } else {
            // Second semester: July to December
            $startDate = Carbon::createFromDate($year, 7, 1)->startOfDay();
            $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();
        }

        return [
            'start' => $startDate,
            'end' => $endDate
        ];
    }


    private function calculateBookQuantity($stock, $endDate)
    {
        // For current dates or future dates, use current stock quantity
        if ($endDate->isToday() || $endDate->isFuture()) {
            return $stock->quantity_on_hand;
        }

        // For historical dates, calculate running balance from transactions
        // Get all transactions for this supply and fund cluster up to the end date
        $transactions = SupplyTransaction::where('supply_id', $stock->supply_id)
            ->where('fund_cluster', $stock->fund_cluster) // Match exact fund cluster
            ->where('transaction_date', '<=', $endDate)
            ->orderBy('transaction_date', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        $runningBalance = 0;

        // Process each transaction to calculate running balance
        // This follows the same logic as your Stock Card and Supply Ledger Card
        foreach ($transactions as $transaction) {
            if ($transaction->transaction_type == 'receipt') {
                $runningBalance += $transaction->quantity;
            } elseif ($transaction->transaction_type == 'issue') {
                $runningBalance -= $transaction->quantity;
            } elseif ($transaction->transaction_type == 'adjustment') {
                // For adjustments, use the balance quantity if available
                if ($transaction->balance_quantity !== null) {
                    $runningBalance = $transaction->balance_quantity;
                }
            }
        }

        return max(0, $runningBalance);
    }

    private function calculateBookQuantityAlternative($stock, $endDate)
    {
        // If the end date is today or future, use current stock quantity
        if ($endDate->isToday() || $endDate->isFuture()) {
            return $stock->quantity_on_hand;
        }

        // Otherwise, calculate from transactions up to that date
        return $this->calculateBookQuantity($stock, $endDate);
    }

}
