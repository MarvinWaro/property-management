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

        // SIMPLE FIX: Count transactions this month using created_at (when they were actually recorded)
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $transactionsThisMonth = SupplyTransaction::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // Get percentage change from last month
        $lastMonth = Carbon::now()->subMonth();
        $transactionsLastMonth = SupplyTransaction::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
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
            ->orderBy('created_at', 'asc')
            ->paginate(5);

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
            'monthlyTransactions' // NEW: Add chart data
        ));
    }

    /**
     * NEW: Get monthly transactions data for the chart
     * Uses your existing SupplyTransaction model
     */
    private function getMonthlyTransactionsData()
    {
        try {
            // Get transactions data for the last 3 years using your SupplyTransaction model
            $transactions = SupplyTransaction::select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(*) as total')
                )
                ->where('created_at', '>=', Carbon::now()->subYears(3)) // Get last 3 years of data
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();

            // Format data for Chart.js
            $monthlyData = [];
            foreach ($transactions as $transaction) {
                $monthlyData[$transaction->year][$transaction->month] = $transaction->total;
            }

            return $monthlyData;

        } catch (\Exception $e) {
            // Log the error and return empty array if something goes wrong
            \Log::error('Error fetching monthly transactions data: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * OPTIONAL: Alternative method if you want to include RIS data as well
     * You can use this instead of the above method if you want to count RIS slips too
     */
    private function getMonthlyTransactionsDataWithRIS()
    {
        try {
            // Get SupplyTransaction data
            $supplyTransactions = SupplyTransaction::select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(*) as total')
                )
                ->where('created_at', '>=', Carbon::now()->subYears(3))
                ->groupBy('year', 'month')
                ->get();

            // Get RIS data (if you want to include RIS slips in the count)
            $risTransactions = RisSlip::select(
                    DB::raw('YEAR(created_at) as year'),
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
     */
    private function getTransactionAnalytics()
    {
        try {
            $analytics = [
                'total_supply_transactions' => SupplyTransaction::count(),
                'total_ris_slips' => RisSlip::count(),
                'transactions_this_year' => SupplyTransaction::whereYear('created_at', Carbon::now()->year)->count(),
                'average_monthly_transactions' => round(SupplyTransaction::count() / max(1, SupplyTransaction::selectRaw('COUNT(DISTINCT YEAR(created_at), MONTH(created_at)) as months')->value('months'))),
                'most_active_month' => SupplyTransaction::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
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
}
