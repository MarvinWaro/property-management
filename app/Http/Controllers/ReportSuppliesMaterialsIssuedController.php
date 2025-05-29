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
                'department' => $firstItem->department->name ?? 'N/A',
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
        });

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
     * Generate detailed RSMI report (by supply item)
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

        $entityName = 'COMMISSION ON HIGHER EDUCATION REGIONAL OFFICE XII';

        return view('rsmi.detailed', compact(
            'reportData',
            'month',
            'startDate',
            'endDate',
            'entityName',
            'fundCluster',
            'departmentId'
        ));
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
                    'department' => $firstItem->department->name ?? 'N/A',
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
            });

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
}
