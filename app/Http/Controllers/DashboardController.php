<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Location;
use App\Models\User;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Supply;
use App\Models\SupplyStock;
use App\Models\SupplyTransaction;
use App\Models\RisSlip;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; // Add this line

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        // Get search query if exists
        $search = $request->get('search');

        // FIXED: Employee count (including staff, admin, and cao)
        $employeeCount = User::whereIn('role', ['staff', 'admin', 'cao'])->count();

        // Total supplies count
        $totalSupplies = Supply::count();

        // Get stock items for debugging
        $stockItems = SupplyStock::with('supply')
            ->where('status', 'available')
            ->select('supply_id', 'quantity_on_hand', 'unit_cost', 'status', 'total_cost', DB::raw('quantity_on_hand * unit_cost as calculated_value'))
            ->get();

        // Total stock value - Using the stored total_cost value from the database
        $totalStockValue = SupplyStock::where('status', 'available')
            ->sum('total_cost');

        // Low stock items - Using raw DB query that doesn't rely on relationship
        $lowStockItems = DB::table('supplies')
            ->leftJoin(DB::raw('(SELECT supply_id, SUM(quantity_on_hand) as total_qty FROM supply_stocks WHERE status = "available" GROUP BY supply_id) as ss'),
                'supplies.supply_id', '=', 'ss.supply_id')
            ->whereRaw('COALESCE(ss.total_qty, 0) <= supplies.reorder_point')
            ->count();

        // UPDATED: Count transactions this month using transaction_date (business date) for consistency
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $transactionsThisMonth = SupplyTransaction::whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->count();

        // Get percentage change from last month (also using transaction_date)
        $lastMonth = Carbon::now()->subMonth();
        $transactionsLastMonth = SupplyTransaction::whereMonth('transaction_date', $lastMonth->month)
            ->whereYear('transaction_date', $lastMonth->year)
            ->count();

        $transactionPercentChange = $transactionsLastMonth > 0
            ? round((($transactionsThisMonth - $transactionsLastMonth) / $transactionsLastMonth) * 100, 1)
            : 0;

        // Get the latest transaction created/updated time (based on database record change)
        $latestTransactionUpdate = SupplyTransaction::latest('created_at')->first();
        $lastTransactionUpdateTime = $latestTransactionUpdate ? $latestTransactionUpdate->created_at : null;

        // Stats for the page (keep existing ones)
        $totalUsers = User::count();
        $lastUpdatedRecord = User::latest('updated_at')->first();
        $lastUpdated = $lastUpdatedRecord ? $lastUpdatedRecord->updated_at : null;
        $totalProperties = Property::count();
        $totalLocations = Location::count();

        // NEW: Get monthly transactions data for chart
        $monthlyTransactions = $this->getMonthlyTransactionsData();

        // NEW: Get department distribution data for donut chart
        $departmentTransactions = $this->getDepartmentTransactionsData();

        // NEW: Get stock status data for donut chart
        $stockStatusData = $this->getStockStatusData();

        // For user listing with search functionality
        $users = User::with('department', 'designation')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");

                    // Search in department relationship
                    $q->orWhereHas('department', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', "%{$search}%");
                    });

                    // Search in designation relationship
                    $q->orWhereHas('designation', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', "%{$search}%");
                    });
                });
            })
            ->orderByRaw('CASE
                WHEN role = "admin" THEN 1
                WHEN role = "cao" THEN 2
                ELSE 3
            END')
            ->orderBy('id', 'asc')
            ->paginate(80);

        // Append search parameter to pagination links
        if ($search) {
            $users->appends(['search' => $search]);
        }

        // For the create form
        $departments = Department::all();
        $designations = Designation::all();

        return view('dashboard', compact(
            'employeeCount',  // Changed from staffCount to employeeCount
            'totalSupplies',
            'totalStockValue',
            'lowStockItems',
            'transactionsThisMonth',
            'transactionPercentChange',
            'totalUsers',
            'lastUpdated',
            'totalProperties',
            'totalLocations',
            'users',
            'departments',
            'designations',
            'search',
            'stockItems',
            'lastTransactionUpdateTime',
            'monthlyTransactions', // NEW: Add chart data
            'departmentTransactions', // NEW: Add department donut chart data
            'stockStatusData' // NEW: Add stock status donut chart data
        ));
    }

    /**
     * NEW: Get monthly transactions data for the chart with transaction type breakdown
     * Uses transaction_date (business date) and groups by transaction type
     */
    private function getMonthlyTransactionsData()
    {
        try {
            // Get transactions data for the last 3 years grouped by transaction type
            $transactions = SupplyTransaction::select(
                    'transaction_type',
                    DB::raw('YEAR(transaction_date) as year'),
                    DB::raw('MONTH(transaction_date) as month'),
                    DB::raw('COUNT(*) as total')
                )
                ->where('transaction_date', '>=', Carbon::now()->subYears(3))
                ->groupBy('transaction_type', 'year', 'month')
                ->orderBy('transaction_type', 'asc')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();

            // Format data for Chart.js: [transaction_type][year][month] = count
            $monthlyData = [];
            foreach ($transactions as $transaction) {
                $monthlyData[$transaction->transaction_type][$transaction->year][$transaction->month] = $transaction->total;
            }

            return $monthlyData;

        } catch (\Exception $e) {
            // Log the error and return empty array if something goes wrong
            \Log::error('Error fetching monthly transactions data: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * NEW: Get department transactions data for donut chart
     * Only includes transactions with a real department_id
     */
    private function getDepartmentTransactionsData()
    {
        try {
            $transactions = SupplyTransaction::with('department')
                ->whereNotNull('department_id')
                ->select(
                    'department_id',
                    'transaction_type',
                    DB::raw('YEAR(transaction_date) as year'),
                    DB::raw('MONTH(transaction_date) as month'),
                    DB::raw('COUNT(*) as total')
                )
                ->where('transaction_date', '>=', Carbon::now()->subYears(3))
                ->groupBy('department_id','transaction_type','year','month')
                ->orderBy('year','asc')
                ->orderBy('month','asc')
                ->get();

            $departmentData = [];

            foreach ($transactions as $t) {
                // skip if no relation
                if (! $t->department) continue;

                $type = $t->transaction_type;
                $dept = $t->department->name;
                $departmentData[$type][$dept][$t->year][$t->month] = $t->total;
            }

            return $departmentData;

        } catch (\Exception $e) {
            Log::error('Error fetching department transactions data: '.$e->getMessage());
            return [];
        }
    }



    /**
     * NEW: Get stock status data for donut chart
     * Categorizes supplies based on current stock levels vs reorder points
     */
    private function getStockStatusData()
    {
        try {
            // Get supplies with their current stock levels
            $supplies = DB::table('supplies')
                ->leftJoin(DB::raw('(SELECT supply_id, SUM(quantity_on_hand) as total_qty FROM supply_stocks WHERE status = "available" GROUP BY supply_id) as ss'),
                    'supplies.supply_id', '=', 'ss.supply_id')
                ->select(
                    'supplies.supply_id',
                    'supplies.item_name',
                    'supplies.reorder_point',
                    DB::raw('COALESCE(ss.total_qty, 0) as current_stock')
                )
                ->get();

            $wellStocked = 0;
            $lowStock = 0;
            $outOfStock = 0;

            foreach ($supplies as $supply) {
                $currentStock = (int) $supply->current_stock;
                $reorderPoint = (int) $supply->reorder_point;

                if ($currentStock == 0) {
                    $outOfStock++;
                } elseif ($currentStock <= $reorderPoint) {
                    $lowStock++;
                } else {
                    $wellStocked++;
                }
            }

            return [
                'wellStocked' => $wellStocked,
                'lowStock' => $lowStock,
                'outOfStock' => $outOfStock
            ];

        } catch (\Exception $e) {
            \Log::error('Error fetching stock status data: ' . $e->getMessage());
            return [
                'wellStocked' => 0,
                'lowStock' => 0,
                'outOfStock' => 0
            ];
        }
    }


    private function getMonthlyTransactionsDataWithRIS()
    {
        try {
            // Get SupplyTransaction data using transaction_date
            $supplyTransactions = SupplyTransaction::select(
                    DB::raw('YEAR(transaction_date) as year'),
                    DB::raw('MONTH(transaction_date) as month'),
                    DB::raw('COUNT(*) as total')
                )
                ->where('transaction_date', '>=', Carbon::now()->subYears(3))
                ->groupBy('year', 'month')
                ->get();

            // Get RIS data (if you want to include RIS slips in the count)
            // Adjust the date field based on your RIS table structure
            $risTransactions = RisSlip::select(
                    DB::raw('YEAR(created_at) as year'), // Adjust this field if RIS has a different date field
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(*) as total')
                )
                ->where('created_at', '>=', Carbon::now()->subYears(3))
                ->groupBy('year', 'month')
                ->get();

            // Combine both transaction types
            $allTransactions = $supplyTransactions->concat($risTransactions);

            // Aggregate the combined data
            $monthlyData = [];
            foreach ($allTransactions as $transaction) {
                if (!isset($monthlyData[$transaction->year])) {
                    $monthlyData[$transaction->year] = [];
                }
                if (!isset($monthlyData[$transaction->year][$transaction->month])) {
                    $monthlyData[$transaction->year][$transaction->month] = 0;
                }
                $monthlyData[$transaction->year][$transaction->month] += $transaction->total;
            }

            return $monthlyData;

        } catch (\Exception $e) {
            \Log::error('Error fetching monthly transactions data with RIS: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * OPTIONAL: Get additional analytics data for future use
     * Updated to use transaction_date for consistency
     */
    private function getTransactionAnalytics()
    {
        try {
            $analytics = [
                'total_supply_transactions' => SupplyTransaction::count(),
                'total_ris_slips' => RisSlip::count(),
                'transactions_this_year' => SupplyTransaction::whereYear('transaction_date', Carbon::now()->year)->count(),
                'average_monthly_transactions' => round(SupplyTransaction::count() / max(1, SupplyTransaction::selectRaw('COUNT(DISTINCT YEAR(transaction_date), MONTH(transaction_date)) as months')->value('months'))),
                'most_active_month' => SupplyTransaction::selectRaw('MONTH(transaction_date) as month, COUNT(*) as total')
                    ->groupBy('month')
                    ->orderBy('total', 'desc')
                    ->first(),
            ];

            return $analytics;
        } catch (\Exception $e) {
            \Log::error('Error fetching transaction analytics: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * DEBUG: Temporary method to check what data is being returned
     * Updated for transaction type breakdown and donut chart data
     */
    public function debugChartData()
    {
        $data = $this->getMonthlyTransactionsData();
        $deptData = $this->getDepartmentTransactionsData();
        $stockData = $this->getStockStatusData();

        // Also get raw query results for debugging
        $rawData = SupplyTransaction::select(
                'transaction_type',
                DB::raw('YEAR(transaction_date) as year'),
                DB::raw('MONTH(transaction_date) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('MIN(transaction_date) as first_date'),
                DB::raw('MAX(transaction_date) as last_date')
            )
            ->where('transaction_date', '>=', Carbon::now()->subYears(3))
            ->groupBy('transaction_type', 'year', 'month')
            ->orderBy('transaction_type', 'asc')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Get transaction type counts
        $typeCounts = SupplyTransaction::select('transaction_type', DB::raw('COUNT(*) as total'))
            ->groupBy('transaction_type')
            ->get();

        // Get department counts
        $deptCounts = SupplyTransaction::with('department')
            ->select('department_id', DB::raw('COUNT(*) as total'))
            ->groupBy('department_id')
            ->get()
            ->map(function($item) {
                return [
                    'department' => $item->department ? $item->department->name : 'Unknown',
                    'total' => $item->total
                ];
            });

        return response()->json([
            'line_chart_data' => $data,
            'department_donut_data' => $deptData,
            'stock_status_data' => $stockData,
            'raw_transactions_data' => $rawData,
            'transaction_type_counts' => $typeCounts,
            'department_counts' => $deptCounts,
            'total_transactions' => SupplyTransaction::count(),
            'total_supplies' => Supply::count(),
            'date_range' => [
                'from' => Carbon::now()->subYears(3)->format('Y-m-d'),
                'to' => Carbon::now()->format('Y-m-d')
            ]
        ]);
    }

    // Keep your existing assets method unchanged
    public function assets()
    {
        // Count all users
        $totalUsers = User::count();

        // Retrieve the most recently updated user record
        $lastUpdatedRecord = User::orderBy('updated_at', 'desc')->first();
        $lastUpdated = $lastUpdatedRecord ? $lastUpdatedRecord->updated_at : null;

        // Count all properties
        $totalProperties = Property::count();

        // Count all locations
        $totalLocations = Location::count();

        return view('assets', compact('totalUsers', 'lastUpdated', 'totalProperties', 'totalLocations'));
    }


    /**
     * Switch admin/cao to user mode for requesting supplies
     */
    public function switchToUserMode(Request $request)
    {
        // Store in session that admin is in user mode
        session(['admin_user_mode' => true]);
        session(['original_role' => Auth::user()->role]);

        return redirect()->route('admin.user-dashboard')->with('success', 'Switched to User Mode. You can now request supplies.');
    }

    /**
     * Switch back to admin mode
     */
    public function switchToAdminMode(Request $request)
    {
        // Remove user mode session
        session()->forget(['admin_user_mode', 'original_role']);

        return redirect()->route('dashboard')->with('success', 'Switched back to Admin Mode.');
    }

}
