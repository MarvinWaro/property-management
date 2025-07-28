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

                // Calculate weighted average unit cost (same as Supply Ledger Card)
                $weightedAverageUnitCost = $this->calculateWeightedAverageUnitCost($stock, $endDate);

                // Only include items with book balance
                if ($bookQuantity > 0) {
                    $reportData->push([
                        'stock_no' => $supply->stock_no,
                        'item_name' => $supply->item_name,
                        'description' => $supply->description,
                        'unit' => $supply->unit_of_measurement,
                        'unit_value' => $weightedAverageUnitCost,
                        'balance_per_card' => $bookQuantity,
                    ]);
                }
            }
        }

        // Sort by stock number
        $reportData = $reportData->sortBy('stock_no')->values();

        // Create Excel file
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set page setup
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_LEGAL);
        $sheet->getPageMargins()->setTop(0.5)->setRight(0.5)->setLeft(0.5)->setBottom(0.5);

        // ========== EXACT TEMPLATE REPLICATION ==========

        // Appendix 66 (top right) - Cell J1
        $sheet->setCellValue('J1', 'Appendix 66');
        $sheet->getStyle('J1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('J1')->getFont()->setItalic(true)->setSize(10);

        // Row 3: Main Title - centered across columns A to J
        $sheet->mergeCells('A3:J3');
        $sheet->setCellValue('A3', 'REPORT ON THE PHYSICAL COUNT OF INVENTORIES');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Row 4: Underlined blank area above subtitle - merged but underline centered
        $sheet->mergeCells('A4:J4');
        $sheet->setCellValue('A4', '___________________');
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Row 5: Subtitle - centered
        $sheet->mergeCells('A5:J5');
        $sheet->setCellValue('A5', '(Type of Inventory Item)');
        $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A5')->getFont()->setSize(10);

        // Row 6: As at - directly under subtitle, merged and with blank line for manual filling
        $sheet->mergeCells('A6:J6');
        $sheet->setCellValue('A6', 'As at ______________________');
        $sheet->getStyle('A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A6')->getFont()->setSize(10);

        // Row 8: Fund Cluster - left aligned with shorter underline
        $sheet->setCellValue('A8', 'Fund Cluster :');
        $sheet->mergeCells('B8:D8'); // Shorter merge - only B to D
        $sheet->setCellValue('B8', ''); // Leave blank for manual entry
        $sheet->getStyle('B8:D8')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Row 10: Accountability section - ONE MERGED LINE from A to J
        $sheet->mergeCells('A10:J10');
        $sheet->setCellValue('A10', 'For which ___(Name of Accountable Officer)_________, _  (Official Designation)___, __________(Entity Name)______________ is accountable, having assumed such accountability on ___(Date of Assumption)____.');
        $sheet->getStyle('A10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('A10')->getFont()->setSize(10);

        // Table Headers - Rows 12 & 13
        // Row 12: Main headers
        $sheet->setCellValue('A12', 'Article');
        $sheet->setCellValue('B12', 'Description');
        $sheet->setCellValue('C12', 'Stock Number');
        $sheet->setCellValue('D12', 'Unit of Measure');
        $sheet->setCellValue('E12', 'Unit Value');
        $sheet->setCellValue('F12', 'Balance Per Card');
        $sheet->setCellValue('G12', 'On Hand Per Count');

        // Shortage/Overage spans H12:I12
        $sheet->mergeCells('H12:I12');
        $sheet->setCellValue('H12', 'Shortage/Overage');

        $sheet->setCellValue('J12', 'Remarks');

        // Row 13: Sub-headers
        $sheet->setCellValue('F13', '(Quantity)');
        $sheet->setCellValue('G13', '(Quantity)');
        $sheet->setCellValue('H13', 'Quantity');
        $sheet->setCellValue('I13', 'Value');

        // Merge cells for single headers (rows 12-13)
        $sheet->mergeCells('A12:A13');
        $sheet->mergeCells('B12:B13');
        $sheet->mergeCells('C12:C13');
        $sheet->mergeCells('D12:D13');
        $sheet->mergeCells('E12:E13');
        $sheet->mergeCells('J12:J13');

        // Style headers with borders
        $headerRange = 'A12:J13';
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

        // Data rows start at row 14
        $currentRow = 14;
        $articleNumber = 1;

        foreach ($reportData as $item) {
            $sheet->setCellValue("A{$currentRow}", $articleNumber);
            $sheet->setCellValue("B{$currentRow}", $item['item_name'] . ($item['description'] ? ', ' . $item['description'] : ''));
            $sheet->setCellValue("C{$currentRow}", $item['stock_no']);
            $sheet->setCellValue("D{$currentRow}", $item['unit']);
            $sheet->setCellValue("E{$currentRow}", $item['unit_value']);
            $sheet->setCellValue("F{$currentRow}", $item['balance_per_card']);

            // Leave blank for manual entry
            $sheet->setCellValue("G{$currentRow}", '');
            $sheet->setCellValue("H{$currentRow}", '');
            $sheet->setCellValue("I{$currentRow}", '');
            $sheet->setCellValue("J{$currentRow}", '');

            // Format numbers
            $sheet->getStyle("E{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle("F{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');

            // Borders for data row
            $sheet->getStyle("A{$currentRow}:J{$currentRow}")->getBorders()
                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Alignment
            $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("C{$currentRow}:J{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $currentRow++;
            $articleNumber++;
        }

        // Add empty rows with borders (minimum 15 empty rows)
        $emptyRowsToAdd = max(15 - $reportData->count(), 5);
        for ($i = 0; $i < $emptyRowsToAdd; $i++) {
            $sheet->getStyle("A{$currentRow}:J{$currentRow}")->getBorders()
                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $currentRow++;
        }

        // ========== CORRECTED SIGNATORY SECTION (COA TEMPLATE FORMAT) ==========
        // Add spacing before signature section
        $currentRow += 2;

        $signatureStartRow = $currentRow;

        // Certified Correct by section (NO MERGE - individual cells as per COA template)
        // Row 1: "Certified Correct by:" in column A
        $sheet->setCellValue("A{$signatureStartRow}", "Certified Correct by:");
        $sheet->getStyle("A{$signatureStartRow}")->applyFromArray([
            'font' => ['size' => 10, 'name' => 'Arial'],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
        ]);

        // Row 2: Signature line in column B (1 row below)
        $signatureLineRow = $signatureStartRow + 1;
        $sheet->setCellValue("B{$signatureLineRow}", "_______________________");
        $sheet->getStyle("B{$signatureLineRow}")->applyFromArray([
            'font' => ['size' => 10, 'name' => 'Arial'],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Row 3: Description in column B (1 row below signature line) - ALL IN ONE CELL
        $descriptionRow = $signatureLineRow + 1;
        $sheet->setCellValue("B{$descriptionRow}", "Signature over Printed Name of Inventory Committee Chair and Members");
        $sheet->getStyle("B{$descriptionRow}")->applyFromArray([
            'font' => ['size' => 9, 'name' => 'Arial'],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true]
        ]);

        // TODO: Add "Approved by:" and "Verified by:" sections
        // Please specify the column layout for these sections so I can complete them
        // For now, leaving space for you to clarify the exact format

        // Set column widths to match template exactly
        $sheet->getColumnDimension('A')->setWidth(8);   // Article
        $sheet->getColumnDimension('B')->setWidth(25);  // Description
        $sheet->getColumnDimension('C')->setWidth(12);  // Stock Number
        $sheet->getColumnDimension('D')->setWidth(10);  // Unit of Measure
        $sheet->getColumnDimension('E')->setWidth(12);  // Unit Value
        $sheet->getColumnDimension('F')->setWidth(12);  // Balance Per Card
        $sheet->getColumnDimension('G')->setWidth(12);  // On Hand Per Count
        $sheet->getColumnDimension('H')->setWidth(10);  // Shortage/Overage Quantity
        $sheet->getColumnDimension('I')->setWidth(10);  // Shortage/Overage Value
        $sheet->getColumnDimension('J')->setWidth(15);  // Remarks

        // Write file
        $writer = new Xlsx($spreadsheet);
        $filename = 'RPCI_' . $year . '_S' . $semester . ($fundCluster ? "_FC{$fundCluster}" : "_AllFC") . '.xlsx';

        \Log::info('RPCI Excel Export Generated', [
            'year' => $year,
            'semester' => $semester,
            'fund_cluster' => $fundCluster ?? 'All',
            'items_count' => $reportData->count(),
            'filename' => $filename,
            'signature_section_row' => $signatureStartRow
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
