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

        // Get fund clusters
        $fundClusters = DB::table('supply_stocks')
            ->select('fund_cluster')
            ->distinct()
            ->whereNotNull('fund_cluster')
            ->pluck('fund_cluster');

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

        // Get transactions for the period
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

        // Group by supply
        $reportData = $transactions->groupBy('supply_id')->map(function($items) {
            $supply = $items->first()->supply;
            $totalQuantity = $items->sum('quantity');
            $totalCost = $items->sum('total_cost');

            return [
                'stock_no' => $supply->stock_no,
                'item_name' => $supply->item_name,
                'unit' => $supply->unit_of_measurement,
                'category' => $supply->category->name ?? 'Uncategorized',
                'total_quantity' => $totalQuantity,
                'average_unit_cost' => $totalQuantity > 0 ? $totalCost / $totalQuantity : 0,
                'total_cost' => $totalCost,
                'transactions' => $items->map(function($item) {
                    return [
                        'ris_no' => $item->reference_no,
                        'department' => $item->department->name ?? 'N/A',
                        'date' => $item->transaction_date,
                        'quantity' => $item->quantity,
                        'unit_cost' => $item->unit_cost,
                        'total' => $item->total_cost
                    ];
                })
            ];
        })->sortBy('stock_no');

        // Generate analytics data for charts
        $analyticsData = $this->generateDetailedAnalytics($transactions, $startDate, $endDate, $fundCluster);

        $entityName = 'COMMISSION ON HIGHER EDUCATION REGIONAL OFFICE XII';

        return view('rsmi.detailed', compact(
            'reportData',
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
     * Get monthly comparison data
     */
    public function monthlyComparison(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $fundCluster = $request->get('fund_cluster', '101');

        $monthlyData = collect();

        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            $monthlyTotal = SupplyTransaction::where('transaction_type', 'issue')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->whereHas('supply.stocks', function($q) use ($fundCluster) {
                    $q->where('fund_cluster', $fundCluster);
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

        /**
     * Export RSMI to PDF with proper COA format
     * Add this method to your App\Http\Controllers\ReportSuppliesMaterialsIssuedController class
     */
    public function exportPdfFormatted(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $departmentId = $request->get('department_id');
        $fundCluster = $request->get('fund_cluster', '101');

        // Parse month
        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = Carbon::parse($month . '-01')->endOfMonth();

        // Get transactions for the period
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

        $entityName = 'COMMISSION ON HIGHER EDUCATION REGIONAL OFFICE XII';

        // Generate PDF using the formatted view
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('rsmi.pdf-formatted', compact(
            'reportData',
            'month',
            'startDate',
            'endDate',
            'entityName',
            'fundCluster'
        ));

        // Set paper size to legal landscape for proper formatting
        $pdf->setPaper('legal', 'landscape');

        // Set additional options for better rendering
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Arial'
        ]);

        $filename = "RSMI-" . strtoupper($startDate->format('F-Y')) . "-FC{$fundCluster}.pdf";
        return $pdf->download($filename);
    }
}
