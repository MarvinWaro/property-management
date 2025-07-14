<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\SupplyTransaction;
use App\Models\RisSlip;
use App\Models\RisItem;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


// Add this to your imports at the top of your controller
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\RichText\Run;

class ReportSuppliesMaterialsIssuedController extends Controller
{
    /**
     * Display RSMI main page with filters
     */
    public function index(Request $request)
    {
        $selectedMonth = $request->get('month', Carbon::now()->format('Y-m'));
        $selectedDepartment = $request->get('department_id');
        $selectedFundCluster = $request->get('fund_cluster', '101');

        // Get available months from RIS slips that have been posted
        $availableMonths = RisSlip::where('status', 'posted')
            ->whereNotNull('issued_at')
            ->selectRaw('DATE_FORMAT(issued_at, "%Y-%m") as month')
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month');

        // Get departments
        $departments = Department::orderBy('name')->get();

        // FIXED: Get fund clusters from actual RIS slips that have been posted
        $fundClusters = RisSlip::where('status', 'posted')
            ->whereNotNull('issued_at')
            ->whereNotNull('fund_cluster')
            ->distinct()
            ->pluck('fund_cluster')
            ->sort()
            ->values();

        // If no fund clusters found from RIS slips, fallback to supply_stocks
        if ($fundClusters->isEmpty()) {
            $fundClusters = DB::table('supply_stocks')
                ->select('fund_cluster')
                ->distinct()
                ->whereNotNull('fund_cluster')
                ->pluck('fund_cluster');
        }

        return view('rsmi.index', compact(
            'availableMonths',
            'departments',
            'fundClusters',
            'selectedMonth',
            'selectedDepartment',
            'selectedFundCluster'
        ));
    }

    /**
     * Generate RSMI report
     */
    public function generate(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $departmentId = $request->get('department_id');
        $fundCluster = $request->get('fund_cluster', '101');

        // Parse month
        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = Carbon::parse($month . '-01')->endOfMonth();

        // FIXED: Get transactions for the period filtered by RIS slip fund cluster
        $transactionsQuery = SupplyTransaction::with(['supply.category', 'department'])
            ->where('transaction_type', 'issue')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->whereIn('reference_no', function($query) use ($fundCluster) {
                $query->select('ris_no')
                    ->from('ris_slips')
                    ->where('fund_cluster', $fundCluster)
                    ->where('status', 'posted')
                    ->whereNotNull('issued_at');
            });

        if ($departmentId) {
            $transactionsQuery->where('department_id', $departmentId);
        }

        $transactions = $transactionsQuery->get();

        // Group by RIS number
        $reportData = $transactions->groupBy('reference_no')->map(function($items, $risNo) {
            $firstItem = $items->first();
            return [
                'ris_no' => $risNo,
                'department' => 'CHEDRO XII', // Use constant responsibility center code
                'issued_date' => $firstItem->transaction_date,
                'items' => $items->map(function($item) {
                    return [
                        'stock_no' => $item->supply->stock_no,
                        'item_name' => $item->supply->item_name,
                        'unit' => $item->supply->unit_of_measurement,
                        'quantity_issued' => $item->quantity,
                        'unit_cost' => $item->unit_cost,
                        'total_cost' => $item->total_cost
                    ];
                })
            ];
        })->sortKeys(); // Sort by RIS number (ascending)

        // Calculate summary
        $summary = [
            'total_items' => $transactions->count(),
            'total_cost' => $transactions->sum('total_cost'),
            'unique_supplies' => $transactions->pluck('supply_id')->unique()->count(),
            'departments_served' => $transactions->pluck('department_id')->unique()->count()
        ];

        // Get entity information
        $entityName = 'COMMISSION ON HIGHER EDUCATION REGIONAL OFFICE XII';

        return view('rsmi.report', compact(
            'reportData',
            'summary',
            'month',
            'startDate',
            'endDate',
            'entityName',
            'fundCluster',
            'departmentId'
        ));
    }

    /**
     * Generate detailed RSMI report (by supply item) with analytics data
     */
    public function detailed(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'month' => 'nullable|date_format:Y-m',
            'fund_cluster' => 'nullable|in:101,151',
            'department_id' => 'nullable|exists:departments,id'
        ]);

        $month = $validated['month'] ?? Carbon::now()->format('Y-m');
        $fundCluster = $validated['fund_cluster'] ?? null; // Allow null for "All Fund Clusters"
        $departmentId = $validated['department_id'] ?? null;

        // Parse month
        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = Carbon::parse($month . '-01')->endOfMonth();

        // Get available months for the dropdown (from posted RIS slips)
        $availableMonths = RisSlip::where('status', 'posted')
            ->whereNotNull('issued_at')
            ->whereHas('items', function($query) {
                $query->where('quantity_issued', '>', 0);
            })
            ->selectRaw('DATE_FORMAT(ris_date, "%Y-%m") as month')
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month');

        // If no available months, add current month as fallback
        if ($availableMonths->isEmpty()) {
            $availableMonths = collect([Carbon::now()->format('Y-m')]);
        }

        // Get available fund clusters from posted RIS slips
        $fundClusters = RisSlip::where('status', 'posted')
            ->whereNotNull('issued_at')
            ->whereNotNull('fund_cluster')
            ->distinct()
            ->pluck('fund_cluster')
            ->filter()
            ->sort()
            ->values();

        // If no fund clusters found, provide defaults
        if ($fundClusters->isEmpty()) {
            $fundClusters = collect(['101', '151']);
        }

        // UPDATED: Get all issued items from transactions with proper fund cluster filtering
        $transactionsQuery = SupplyTransaction::with(['supply.category', 'department'])
            ->where('transaction_type', 'issue')
            ->whereBetween('transaction_date', [$startDate, $endDate]);

        // FIXED: Filter by RIS slip fund cluster instead of supply stock fund cluster
        if ($fundCluster) {
            $transactionsQuery->whereIn('reference_no', function($query) use ($fundCluster) {
                $query->select('ris_no')
                    ->from('ris_slips')
                    ->where('fund_cluster', $fundCluster)
                    ->where('status', 'posted')
                    ->whereNotNull('issued_at');
            });
        } else {
            // If no fund cluster specified, still filter by posted RIS slips
            $transactionsQuery->whereIn('reference_no', function($query) {
                $query->select('ris_no')
                    ->from('ris_slips')
                    ->where('status', 'posted')
                    ->whereNotNull('issued_at');
            });
        }

        // Apply department filter if specified
        if ($departmentId) {
            $transactionsQuery->where('department_id', $departmentId);
        }

        $transactions = $transactionsQuery->get();

        // Get RIS slip information for fund cluster data
        $risSlips = RisSlip::whereIn('ris_no', $transactions->pluck('reference_no')->unique())
            ->select('ris_no', 'fund_cluster')
            ->get()
            ->keyBy('ris_no');

        // Group by supply and include fund cluster information
        $reportData = $transactions->groupBy('supply_id')->map(function($items) use ($risSlips) {
            $supply = $items->first()->supply;
            $totalQuantity = $items->sum('quantity');
            $totalCost = $items->sum('total_cost');

            // Map transactions with fund cluster information
            $transactionData = $items->map(function($item) use ($risSlips) {
                $risSlip = $risSlips->get($item->reference_no);
                return [
                    'ris_no' => $item->reference_no,
                    'fund_cluster' => $risSlip ? $risSlip->fund_cluster : null,
                    'department' => $item->department->name ?? 'N/A',
                    'date' => $item->transaction_date,
                    'quantity' => $item->quantity,
                    'unit_cost' => $item->unit_cost,
                    'total' => $item->total_cost
                ];
            });

            return [
                'supply_id' => $supply->supply_id,
                'stock_no' => $supply->stock_no,
                'item_name' => $supply->item_name,
                'description' => $supply->description,
                'unit' => $supply->unit_of_measurement,
                'category' => $supply->category->name ?? 'Uncategorized',
                'total_quantity' => $totalQuantity,
                'average_unit_cost' => $totalQuantity > 0 ? $totalCost / $totalQuantity : 0,
                'total_cost' => $totalCost,
                'transactions' => $transactionData
            ];
        })->sortBy('stock_no')->values();

        // Generate analytics data for charts
        $analyticsData = $this->generateDetailedAnalytics($transactions, $startDate, $endDate, $fundCluster);

        $entityName = 'COMMISSION ON HIGHER EDUCATION REGIONAL OFFICE XII';

        // Log for debugging
        \Log::info('RSMI Detailed Report Generated', [
            'month' => $month,
            'fund_cluster' => $fundCluster,
            'department_id' => $departmentId,
            'total_supplies' => $reportData->count(),
            'total_transactions' => $transactions->count(),
            'available_months_count' => $availableMonths->count(),
            'fund_clusters_count' => $fundClusters->count()
        ]);

        return view('rsmi.detailed', compact(
            'reportData',
            'month',
            'startDate',
            'endDate',
            'entityName',
            'fundCluster',
            'departmentId',
            'analyticsData',
            'availableMonths',
            'fundClusters'
        ));
    }

    /**
     * Generate analytics data for detailed report charts
     */
    private function generateDetailedAnalytics($transactions, $startDate, $endDate, $fundCluster)
    {
        // 1. Category-wise distribution
        $categoryData = $transactions->groupBy('supply.category.name')->map(function($items, $category) {
            return [
                'category' => $category ?: 'Uncategorized',
                'total_cost' => $items->sum('total_cost'),
                'item_count' => $items->count(),
                'unique_items' => $items->pluck('supply_id')->unique()->count()
            ];
        })->sortByDesc('total_cost')->values();

        // 2. Top 10 most expensive items
        $topExpensiveItems = $transactions->groupBy('supply_id')->map(function($items) {
            $supply = $items->first()->supply;
            return [
                'item_name' => $supply->item_name,
                'stock_no' => $supply->stock_no,
                'total_cost' => $items->sum('total_cost'),
                'quantity' => $items->sum('quantity')
            ];
        })->sortByDesc('total_cost')->take(10)->values();

        // 3. Daily issuance trend
        $dailyTrend = $transactions->groupBy(function($item) {
            return Carbon::parse($item->transaction_date)->format('Y-m-d');
        })->map(function($items, $date) {
            return [
                'date' => $date,
                'total_cost' => $items->sum('total_cost'),
                'transaction_count' => $items->count()
            ];
        })->sortBy('date')->values();

        // 4. Department-wise distribution
        $departmentData = $transactions->groupBy('department.name')->map(function($items, $department) {
            return [
                'department' => $department ?: 'N/A',
                'total_cost' => $items->sum('total_cost'),
                'item_count' => $items->count(),
                'unique_items' => $items->pluck('supply_id')->unique()->count()
            ];
        })->sortByDesc('total_cost')->values();

        // 5. RIS-wise summary
        $risData = $transactions->groupBy('reference_no')->map(function($items, $risNo) {
            return [
                'ris_no' => $risNo,
                'total_cost' => $items->sum('total_cost'),
                'item_count' => $items->count(),
                'date' => $items->first()->transaction_date
            ];
        })->sortByDesc('total_cost')->take(10)->values();

        // 6. Unit cost analysis
        $unitCostAnalysis = $transactions->groupBy('supply_id')->map(function($items) {
            $supply = $items->first()->supply;
            $avgCost = $items->avg('unit_cost');
            $maxCost = $items->max('unit_cost');
            $minCost = $items->min('unit_cost');

            return [
                'item_name' => $supply->item_name,
                'avg_cost' => $avgCost,
                'max_cost' => $maxCost,
                'min_cost' => $minCost,
                'cost_variance' => $maxCost - $minCost,
                'total_quantity' => $items->sum('quantity')
            ];
        })->where('cost_variance', '>', 0)->sortByDesc('cost_variance')->take(10)->values();

        // 7. Summary statistics
        $summaryStats = [
            'total_value' => $transactions->sum('total_cost'),
            'total_transactions' => $transactions->count(),
            'unique_items' => $transactions->pluck('supply_id')->unique()->count(),
            'unique_departments' => $transactions->pluck('department_id')->unique()->count(),
            'unique_ris' => $transactions->pluck('reference_no')->unique()->count(),
            'avg_transaction_value' => $transactions->avg('total_cost'),
            'highest_single_transaction' => $transactions->max('total_cost'),
            'date_range' => [
                'start' => $startDate->format('M d, Y'),
                'end' => $endDate->format('M d, Y')
            ]
        ];

        return [
            'categories' => $categoryData,
            'top_expensive_items' => $topExpensiveItems,
            'daily_trend' => $dailyTrend,
            'departments' => $departmentData,
            'ris_summary' => $risData,
            'unit_cost_analysis' => $unitCostAnalysis,
            'summary_stats' => $summaryStats
        ];
    }

    /**
     * Export RSMI to PDF
     */
    public function exportPdf(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $departmentId = $request->get('department_id');
        $fundCluster = $request->get('fund_cluster', '101');
        $format = $request->get('format', 'standard'); // standard or detailed

        // Parse month
        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = Carbon::parse($month . '-01')->endOfMonth();

        // Get the data based on format
        if ($format === 'detailed') {
            // Get all issued items from transactions
            $transactionsQuery = SupplyTransaction::with(['supply.category', 'department'])
                ->where('transaction_type', 'issue')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->whereHas('supply.stocks', function($q) use ($fundCluster) {
                    $q->where('fund_cluster', $fundCluster);
                });

            if ($departmentId) {
                $transactionsQuery->where('department_id', $departmentId);
            }

            $transactions = $transactionsQuery->get();

            // Group by supply for detailed report
            $reportData = $transactions->groupBy('supply_id')->map(function($items) {
                $supply = $items->first()->supply;
                $totalQuantity = $items->sum('quantity');
                $totalCost = $items->sum('total_cost');

                return [
                    'stock_no' => $supply->stock_no,
                    'item_name' => $supply->item_name,
                    'unit' => $supply->unit_of_measurement,
                    'total_quantity' => $totalQuantity,
                    'average_unit_cost' => $totalQuantity > 0 ? $totalCost / $totalQuantity : 0,
                    'total_cost' => $totalCost
                ];
            })->sortBy('stock_no');

            $viewName = 'rsmi.pdf-detailed';
        } else {
            // Standard format - by RIS
            $transactionsQuery = SupplyTransaction::with(['supply.category', 'department'])
                ->where('transaction_type', 'issue')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->whereHas('supply.stocks', function($q) use ($fundCluster) {
                    $q->where('fund_cluster', $fundCluster);
                });

            if ($departmentId) {
                $transactionsQuery->where('department_id', $departmentId);
            }

            $transactions = $transactionsQuery->get();

            // Group by RIS for standard report
            $reportData = $transactions->groupBy('reference_no')->map(function($items, $risNo) {
                $firstItem = $items->first();
                return [
                    'ris_no' => $risNo,
                    'department' => 'CHEDRO XII', // Use constant responsibility center code
                    'items' => $items->map(function($item) {
                        return [
                            'stock_no' => $item->supply->stock_no,
                            'item_name' => $item->supply->item_name,
                            'unit' => $item->supply->unit_of_measurement,
                            'quantity_issued' => $item->quantity,
                            'unit_cost' => $item->unit_cost,
                            'total_cost' => $item->total_cost
                        ];
                    })
                ];
            })->sortKeys(); // Sort by RIS number (ascending)

            $viewName = 'rsmi.pdf-standard';
        }

        $entityName = 'COMMISSION ON HIGHER EDUCATION REGIONAL OFFICE XII';

        $pdf = app('dompdf.wrapper');
        $pdf->loadView($viewName, compact(
            'reportData',
            'month',
            'startDate',
            'endDate',
            'entityName',
            'fundCluster'
        ));

        $pdf->setPaper('legal', 'landscape');

        $filename = "rsmi-{$month}-{$fundCluster}.pdf";
        return $pdf->download($filename);
    }

    /**
     * Get monthly comparison data - FIXED VERSION
     */
    public function monthlyComparison(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $fundCluster = $request->get('fund_cluster', '101');

        $monthlyData = collect();

        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            // FIXED: Filter by RIS slip fund cluster instead of supply stock fund cluster
            $monthlyTotal = SupplyTransaction::where('transaction_type', 'issue')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->whereIn('reference_no', function($query) use ($fundCluster) {
                    $query->select('ris_no')
                        ->from('ris_slips')
                        ->where('fund_cluster', $fundCluster)
                        ->where('status', 'posted')
                        ->whereNotNull('issued_at');
                })
                ->sum('total_cost');

            $monthlyData->push([
                'month' => $startDate->format('F'),
                'total' => $monthlyTotal
            ]);
        }

        return view('rsmi.monthly-comparison', compact('monthlyData', 'year', 'fundCluster'));
    }

    /**
     * Generate RSMI summary report grouped by stock number
     */
    public function summary(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $fundCluster = $request->get('fund_cluster', '101');

        // Parse month
        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = Carbon::parse($month . '-01')->endOfMonth();

        // Get all transactions for the period
        $transactions = SupplyTransaction::with(['supply.category'])
            ->where('transaction_type', 'issue')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->whereHas('supply.stocks', function($q) use ($fundCluster) {
                $q->where('fund_cluster', $fundCluster);
            })
            ->get();

        // Group by stock number and calculate totals
        $summaryData = $transactions->groupBy('supply.stock_no')->map(function($items) {
            $supply = $items->first()->supply;
            $totalQuantity = $items->sum('quantity');
            $totalCost = $items->sum('total_cost');

            return [
                'stock_no' => $supply->stock_no,
                'item_name' => $supply->item_name,
                'unit' => $supply->unit_of_measurement,
                'total_quantity' => $totalQuantity,
                'total_cost' => $totalCost,
            ];
        })->sortBy('stock_no')->values();

        $entityName = 'COMMISSION ON HIGHER EDUCATION REGIONAL OFFICE XII';

        // Generate PDF
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('rsmi.pdf-summary', compact(
            'summaryData',
            'month',
            'startDate',
            'endDate',
            'entityName',
            'fundCluster'
        ));

        $pdf->setPaper('legal', 'landscape');

        $filename = "rsmi-summary-{$month}-{$fundCluster}.pdf";
        return $pdf->download($filename);
    }

    public function analytics(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $departmentId = $request->get('department_id');
        $fundCluster = $request->get('fund_cluster', '101');

        // Parse month
        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = Carbon::parse($month . '-01')->endOfMonth();

        // Get all issued items from transactions
        $transactionsQuery = SupplyTransaction::with(['supply.category', 'department'])
            ->where('transaction_type', 'issue')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->whereHas('supply.stocks', function($q) use ($fundCluster) {
                $q->where('fund_cluster', $fundCluster);
            });

        if ($departmentId) {
            $transactionsQuery->where('department_id', $departmentId);
        }

        $transactions = $transactionsQuery->get();

        // Generate analytics data for charts
        $analyticsData = $this->generateDetailedAnalytics($transactions, $startDate, $endDate, $fundCluster);

        $entityName = 'COMMISSION ON HIGHER EDUCATION REGIONAL OFFICE XII';

        return view('rsmi.analytics', compact(
            'month',
            'startDate',
            'endDate',
            'entityName',
            'fundCluster',
            'departmentId',
            'analyticsData'
        ));
    }

    /**
     * Show yearly analytics for RSMI
     */
    public function yearlyAnalytics(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $fundCluster = $request->get('fund_cluster', '101');

        // Get yearly data
        $yearlyData = $this->generateYearlyAnalytics($year, $fundCluster);

        return view('rsmi.yearly-analytics', compact(
            'year',
            'fundCluster',
            'yearlyData'
        ));
    }

    /**
     * Generate comprehensive yearly analytics data
     */
    private function generateYearlyAnalytics($year, $fundCluster)
    {
        $startDate = Carbon::createFromDate($year, 1, 1)->startOfYear();
        $endDate = Carbon::createFromDate($year, 12, 31)->endOfYear();

        // Get all transactions for the year
        $transactions = SupplyTransaction::with(['supply.category', 'department'])
            ->where('transaction_type', 'issue')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->whereHas('supply.stocks', function($q) use ($fundCluster) {
                $q->where('fund_cluster', $fundCluster);
            })
            ->get();

        // Monthly breakdown
        $monthlyBreakdown = collect();
        for ($month = 1; $month <= 12; $month++) {
            $monthStart = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $monthEnd = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            $monthTransactions = $transactions->whereBetween('transaction_date', [$monthStart, $monthEnd]);

            $monthlyBreakdown->push([
                'month' => $monthStart->format('M'),
                'month_full' => $monthStart->format('F'),
                'total' => $monthTransactions->sum('total_cost'),
                'items' => $monthTransactions->count(),
                'unique_items' => $monthTransactions->pluck('supply_id')->unique()->count()
            ]);
        }

        // Quarterly data
        $quarterlyData = collect([
            [
                'quarter' => 'Q1',
                'months' => [1, 2, 3],
                'total' => $transactions->filter(function($item) {
                    return in_array(Carbon::parse($item->transaction_date)->month, [1, 2, 3]);
                })->sum('total_cost')
            ],
            [
                'quarter' => 'Q2',
                'months' => [4, 5, 6],
                'total' => $transactions->filter(function($item) {
                    return in_array(Carbon::parse($item->transaction_date)->month, [4, 5, 6]);
                })->sum('total_cost')
            ],
            [
                'quarter' => 'Q3',
                'months' => [7, 8, 9],
                'total' => $transactions->filter(function($item) {
                    return in_array(Carbon::parse($item->transaction_date)->month, [7, 8, 9]);
                })->sum('total_cost')
            ],
            [
                'quarter' => 'Q4',
                'months' => [10, 11, 12],
                'total' => $transactions->filter(function($item) {
                    return in_array(Carbon::parse($item->transaction_date)->month, [10, 11, 12]);
                })->sum('total_cost')
            ]
        ]);

        // Top categories (yearly)
        $topCategories = $transactions->groupBy('supply.category.name')->map(function($items, $category) {
            return [
                'category' => $category ?: 'Uncategorized',
                'total' => $items->sum('total_cost'),
                'items' => $items->count(),
                'unique_items' => $items->pluck('supply_id')->unique()->count(),
                'ris_count' => $items->pluck('reference_no')->unique()->count()
            ];
        })->sortByDesc('total')->values();

        // Top items (yearly)
        $topItems = $transactions->groupBy('supply_id')->map(function($items) {
            $supply = $items->first()->supply;
            return [
                'item_name' => $supply->item_name,
                'stock_no' => $supply->stock_no,
                'total' => $items->sum('total_cost'),
                'quantity' => $items->sum('quantity')
            ];
        })->sortByDesc('total')->take(15)->values();

        // Calculate insights
        $peakMonth = $monthlyBreakdown->sortByDesc('total')->first();
        $strongestQuarter = $quarterlyData->sortByDesc('total')->first();
        $mostExpensiveItem = $topItems->first();
        $mostUsedCategory = $topCategories->first();

        // Get last year data for growth calculation
        $lastYearTotal = SupplyTransaction::where('transaction_type', 'issue')
            ->whereYear('transaction_date', $year - 1)
            ->whereHas('supply.stocks', function($q) use ($fundCluster) {
                $q->where('fund_cluster', $fundCluster);
            })
            ->sum('total_cost');

        $currentYearTotal = $transactions->sum('total_cost');
        $growthRate = $lastYearTotal > 0 ? (($currentYearTotal - $lastYearTotal) / $lastYearTotal) * 100 : 0;

        // Summary statistics
        $summary = [
            'total_value' => $currentYearTotal,
            'total_items' => $transactions->count(),
            'unique_items' => $transactions->pluck('supply_id')->unique()->count(),
            'total_ris' => $transactions->pluck('reference_no')->unique()->count(),
            'avg_monthly' => $currentYearTotal / 12,
            'peak_month' => $peakMonth['month_full'] ?? 'N/A'
        ];

        $insights = [
            'busiest_month' => $monthlyBreakdown->sortByDesc('items')->first()['month_full'] ?? 'N/A',
            'most_expensive_item' => $mostExpensiveItem['item_name'] ?? 'N/A',
            'most_used_category' => $mostUsedCategory['category'] ?? 'N/A',
            'growth_rate' => $growthRate,
            'strongest_quarter' => $strongestQuarter['quarter'] ?? 'N/A',
            'strongest_quarter_value' => $strongestQuarter['total'] ?? 0
        ];

        return [
            'summary' => $summary,
            'monthly_breakdown' => $monthlyBreakdown,
            'quarterly_data' => $quarterlyData,
            'top_categories' => $topCategories,
            'top_items' => $topItems,
            'insights' => $insights
        ];
    }

    public function exportPdfFormatted(Request $request)
    {
        $month        = $request->get('month', Carbon::now()->format('Y-m'));
        $departmentId = $request->get('department_id');
        $fundCluster  = $request->get('fund_cluster', '101');

        // Parse month
        $startDate = Carbon::parse("{$month}-01")->startOfMonth();
        $endDate   = Carbon::parse("{$month}-01")->endOfMonth();

        // Pull transactions
        $transactionsQuery = SupplyTransaction::with(['supply', 'department'])
            ->where('transaction_type','issue')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->whereHas('supply.stocks', fn($q) => $q->where('fund_cluster',$fundCluster));

        if ($departmentId) {
            $transactionsQuery->where('department_id',$departmentId);
        }

        $transactions = $transactionsQuery->get();

        // Group by RIS number
        $reportData = $transactions
            ->groupBy('reference_no')
            ->map(fn($items, $risNo) => [
                'ris_no'       => $risNo,
                'department'   => 'CHEDRO XII',
                'issued_date'  => $items->first()->transaction_date,
                'items'        => $items->map(fn($tx) => [
                    'stock_no'        => $tx->supply->stock_no,
                    // **combine name + description here**:
                    'item_name'       => trim(
                                            $tx->supply->item_name
                                            . (
                                                $tx->supply->description
                                                ?? ', '.$tx->supply->description

                                            )
                                        ),
                    'unit'            => $tx->supply->unit_of_measurement,
                    'quantity_issued' => $tx->quantity,
                    'unit_cost'       => $tx->unit_cost,
                    'total_cost'      => $tx->total_cost,
                ]),
            ])
            ->sortKeys();

        $entityName = 'COMMISSION ON HIGHER EDUCATION REGIONAL OFFICE XII';

        // Render PDF
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('rsmi.pdf-formatted', compact(
            'reportData','month','startDate','endDate','entityName','fundCluster'
        ));
        $pdf->setPaper('legal','landscape');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'      => true,
            'defaultFont'          => 'Arial'
        ]);

        $filename = "RSMI-".strtoupper($startDate->format('F-Y'))."-FC{$fundCluster}.pdf";
        return $pdf->download($filename);
    }

    public function exportExcel(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $departmentId = $request->get('department_id');
        $fundCluster = $request->get('fund_cluster', '101');

        // Parse month
        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = Carbon::parse($month . '-01')->endOfMonth();

        // Get transactions (using your existing logic)
        $transactionsQuery = SupplyTransaction::with(['supply', 'department'])
            ->where('transaction_type', 'issue')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->whereHas('supply.stocks', fn($q) => $q->where('fund_cluster', $fundCluster));

        if ($departmentId) {
            $transactionsQuery->where('department_id', $departmentId);
        }

        $transactions = $transactionsQuery->get();

        // Group by RIS number (using your existing grouping logic)
        $reportData = $transactions
            ->groupBy('reference_no')
            ->map(fn($items, $risNo) => [
                'ris_no' => $risNo,
                'department' => 'CHEDRO XII',
                'issued_date' => $items->first()->transaction_date,
                'items' => $items->map(fn($tx) => [
                    'stock_no' => $tx->supply->stock_no,
                    'item_name' => trim(
                        $tx->supply->item_name .
                        ($tx->supply->description ? ', ' . $tx->supply->description : '')
                    ),
                    'unit' => $tx->supply->unit_of_measurement,
                    'quantity_issued' => $tx->quantity,
                    'unit_cost' => $tx->unit_cost,
                    'total_cost' => $tx->total_cost,
                ]),
            ])
            ->sortKeys();

        $entityName = 'COMMISSION ON HIGHER EDUCATION REGIONAL OFFICE XII';

        // Create Excel file
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set page setup
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_LEGAL);
        $sheet->getPageMargins()->setTop(0.5)->setRight(0.5)->setLeft(0.5)->setBottom(0.5);

        // Appendix 64 (top right)
        $sheet->setCellValue('H1', 'Appendix 64');
        $sheet->getStyle('H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('H1')->getFont()->setItalic(true);

        // Title
        $sheet->mergeCells('A3:H3');
        $sheet->setCellValue('A3', 'REPORT OF SUPPLIES AND MATERIALS ISSUED');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Entity info
        $sheet->setCellValue('A5', 'Entity Name:');
        $sheet->setCellValue('B5', strtoupper($entityName));
        $sheet->mergeCells('B5:E5');
        $sheet->getStyle('A5')->getFont()->setBold(true);
        $sheet->getStyle('B5')->getFont()->setBold(true);
        $sheet->getStyle('B5:E5')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

        $sheet->setCellValue('F5', 'Serial No.:');
        $sheet->setCellValue('G5', '');
        $sheet->mergeCells('G5:H5');
        $sheet->getStyle('F5')->getFont()->setBold(true);
        $sheet->getStyle('G5:H5')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

        $sheet->setCellValue('A6', 'Fund Cluster:');
        $sheet->setCellValue('B6', $fundCluster);
        $sheet->mergeCells('B6:E6');
        $sheet->getStyle('A6')->getFont()->setBold(true);
        $sheet->getStyle('B6')->getFont()->setBold(true);
        $sheet->getStyle('B6:E6')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('B6:E6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $sheet->setCellValue('F6', 'Date:');
        $sheet->setCellValue('G6', $startDate->format('F Y'));
        $sheet->mergeCells('G6:H6');
        $sheet->getStyle('F6')->getFont()->setBold(true);
        $sheet->getStyle('G6')->getFont()->setBold(true);
        $sheet->getStyle('G6:H6')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

        // FIXED: Instructions with proper merged cells and borders (no gap - row 8)
        $instructionRow = 8;

        // Left instruction: "To be filled up by the Supply and/or Property Division/Unit" (A-F merged)
        $sheet->mergeCells("A{$instructionRow}:F{$instructionRow}");
        $sheet->setCellValue("A{$instructionRow}", 'To be filled up by the Supply and/or Property Division/Unit');
        $sheet->getStyle("A{$instructionRow}:F{$instructionRow}")->applyFromArray([
            'font' => ['italic' => true, 'size' => 10],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);

        // Right instruction: "To be filled up by the Accounting Division/Unit" (G-H merged)
        $sheet->mergeCells("G{$instructionRow}:H{$instructionRow}");
        $sheet->setCellValue("G{$instructionRow}", 'To be filled up by the Accounting Division/Unit');
        $sheet->getStyle("G{$instructionRow}:H{$instructionRow}")->applyFromArray([
            'font' => ['italic' => true, 'size' => 10],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);

        // Main table headers (immediately after instructions - row 9)
        $currentRow = 9;
        $headers = [
            'A' => "RIS No.",
            'B' => "Responsibility\nCenter Code",
            'C' => "Stock No.",
            'D' => "Item",
            'E' => "Unit",
            'F' => "Quantity\nIssued",
            'G' => "Unit Cost",
            'H' => "Amount"
        ];

        foreach ($headers as $col => $header) {
            $sheet->setCellValue("{$col}{$currentRow}", $header);
        }

        // Style headers
        $headerRange = "A{$currentRow}:H{$currentRow}";
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
        $sheet->getRowDimension($currentRow)->setRowHeight(30);

        // Data rows
        $currentRow++;
        $totalAmount = 0;
        $recapData = [];

        foreach ($reportData as $risData) {
            foreach ($risData['items'] as $item) {
                $sheet->setCellValue("A{$currentRow}", $risData['ris_no']);
                $sheet->setCellValue("B{$currentRow}", ''); // Responsibility Center Code
                $sheet->setCellValue("C{$currentRow}", $item['stock_no']);
                $sheet->setCellValue("D{$currentRow}", $item['item_name']);
                $sheet->setCellValue("E{$currentRow}", $item['unit']);
                $sheet->setCellValue("F{$currentRow}", $item['quantity_issued']);
                $sheet->setCellValue("G{$currentRow}", $item['unit_cost']);
                $sheet->setCellValue("H{$currentRow}", $item['total_cost']);

                // Format numbers
                $sheet->getStyle("F{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle("G{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle("H{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.00');

                // Apply borders
                $sheet->getStyle("A{$currentRow}:H{$currentRow}")->getBorders()
                    ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // Alignment
                $sheet->getStyle("A{$currentRow}:C{$currentRow}")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("E{$currentRow}:F{$currentRow}")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("G{$currentRow}:H{$currentRow}")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                $totalAmount += $item['total_cost'];

                // Build recap data
                $stockNo = $item['stock_no'];
                if (!isset($recapData[$stockNo])) {
                    $recapData[$stockNo] = [
                        'quantity' => 0,
                        'unit_cost' => $item['unit_cost'],
                        'total_cost' => 0,
                    ];
                }
                $recapData[$stockNo]['quantity'] += $item['quantity_issued'];
                $recapData[$stockNo]['total_cost'] += $item['total_cost'];

                $currentRow++;
            }
        }

        // Total row
        $sheet->mergeCells("A{$currentRow}:G{$currentRow}");
        $sheet->setCellValue("H{$currentRow}", $totalAmount);
        $sheet->getStyle("H{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle("A{$currentRow}:H{$currentRow}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'F0F0F0']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
        ]);

        // Recapitulation - Fixed to match template exactly with correct column positioning
        $currentRow++;

        // First row - "Recapitulation:" spanning columns B-C and "Recapitulation:" spanning columns F-H
        // Column A remains empty
        $sheet->mergeCells("B{$currentRow}:C{$currentRow}");
        $sheet->setCellValue("B{$currentRow}", 'Recapitulation:');
        $sheet->getStyle("B{$currentRow}:C{$currentRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'F0F0F0']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Apply border to column A (empty)
        $sheet->getStyle("A{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Columns D and E remain empty with borders
        $sheet->getStyle("D{$currentRow}:E{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $sheet->mergeCells("F{$currentRow}:H{$currentRow}");
        $sheet->setCellValue("F{$currentRow}", 'Recapitulation:');
        $sheet->getStyle("F{$currentRow}:H{$currentRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'F0F0F0']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Headers row
        $currentRow++;

        // Column A - empty
        $sheet->getStyle("A{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Left side headers (B-C)
        $sheet->setCellValue("B{$currentRow}", 'Stock No.');
        $sheet->setCellValue("C{$currentRow}", 'Quantity');

        // Apply style to left headers
        $sheet->getStyle("B{$currentRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 10],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'F0F0F0']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        $sheet->getStyle("C{$currentRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 10],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'F0F0F0']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Columns D and E - empty with borders
        $sheet->getStyle("D{$currentRow}:E{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Right side headers (F-G-H)
        $sheet->setCellValue("F{$currentRow}", 'Unit Cost');
        $sheet->setCellValue("G{$currentRow}", 'Total Cost');
        $sheet->setCellValue("H{$currentRow}", 'UACS Object Code');

        // Apply style to right headers
        $sheet->getStyle("F{$currentRow}:H{$currentRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 10],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'F0F0F0']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Data rows - only show up to 15 items as in template
        $currentRow++;
        $recapStartRow = $currentRow;
        $recapItemCount = 0;

        foreach ($recapData as $stockNo => $data) {
            // Column A - empty
            $sheet->getStyle("A{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Left side - Stock No. (B) and Quantity (C)
            $sheet->setCellValue("B{$currentRow}", $stockNo);
            $sheet->setCellValue("C{$currentRow}", $data['quantity']);

            // Columns D and E - empty
            $sheet->getStyle("D{$currentRow}:E{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Right side - Unit Cost (F), Total Cost (G), and UACS Object Code (H)
            $sheet->setCellValue("F{$currentRow}", $data['unit_cost']);
            $sheet->setCellValue("G{$currentRow}", $data['total_cost']);
            $sheet->setCellValue("H{$currentRow}", ''); // UACS Object Code - leave empty

            // Format numbers
            $sheet->getStyle("C{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("F{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle("G{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.00');

            // Apply borders to all cells in the row
            $sheet->getStyle("B{$currentRow}:C{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle("F{$currentRow}:H{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Alignment
            $sheet->getStyle("B{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("C{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F{$currentRow}:G{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("H{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $currentRow++;
        }

        // Total row
        // Columns A-E empty
        $sheet->getStyle("A{$currentRow}:E{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Total label and amount in columns F-G
        $sheet->setCellValue("F{$currentRow}", 'Total:');
        $sheet->setCellValue("G{$currentRow}", $totalAmount);
        $sheet->getStyle("G{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.00');

        // Apply style to total row
        $sheet->getStyle("F{$currentRow}:H{$currentRow}")->applyFromArray([
            'font' => ['bold' => true],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
        ]);

        // Apply special alignment for Total label
        $sheet->getStyle("F{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Signatures - Immediately after recapitulation (no gap)
        $currentRow++; // Just move one row down from the total row

        // Left signature box (A-D)
        $sheet->mergeCells("A{$currentRow}:D" . ($currentRow + 6));

        // Add the certification text with actual name
        $certRichText = new RichText();
        $certRichText->createText("I hereby certify to the correctness of the above information.\n\n\n");

        // Underlined name part
        $underlined = $certRichText->createTextRun("ALEA MARIE P. DELOSO");
        $underlined->getFont()->setUnderline(true)->setBold(true);

        // Add the title below the name
        $certRichText->createText("\nSupply and/or Property Custodian");

        $sheet->setCellValue("A{$currentRow}", $certRichText);
        $sheet->getStyle("A{$currentRow}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setWrapText(true);
        $sheet->getStyle("A{$currentRow}:D" . ($currentRow + 6))->getBorders()
            ->getOutline()->setBorderStyle(Border::BORDER_THIN);

        // Column E - empty with border
        $sheet->getStyle("E{$currentRow}:E" . ($currentRow + 6))->getBorders()
            ->getOutline()->setBorderStyle(Border::BORDER_THIN);

        // Right side - "Posted by:" label
        $sheet->setCellValue("F{$currentRow}", "Posted by:");
        $sheet->getStyle("F{$currentRow}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT)
            ->setVertical(Alignment::VERTICAL_TOP);

        // Apply borders to F-H for the entire signature area
        $sheet->getStyle("F{$currentRow}:H" . ($currentRow + 6))->getBorders()
            ->getOutline()->setBorderStyle(Border::BORDER_THIN);

        // Add underlines and text in the right section
        $rightSigRow = $currentRow + 3;

        // Merge F-G for the signature line and text
        $sheet->mergeCells("F{$rightSigRow}:G" . ($rightSigRow + 1));
        $sheet->setCellValue("F{$rightSigRow}", "___________________________\nSignature over Printed Name of\nDesignated Accounting Staff");
        $sheet->getStyle("F{$rightSigRow}:G" . ($rightSigRow + 1))->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setWrapText(true);

        // Date line and text in column H
        $sheet->setCellValue("H{$rightSigRow}", "______________\nDate");
        $sheet->getStyle("H{$rightSigRow}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setWrapText(true);

        // Adjust column widths
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(20);

        // Write file
        $writer = new Xlsx($spreadsheet);
        $filename = 'RSMI_Report_' . $startDate->format('Y_m') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

}
