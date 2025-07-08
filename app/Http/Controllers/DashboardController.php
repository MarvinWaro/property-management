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

        // Staff count (for employees card)
        $staffCount = User::where('role', 'staff')->count();

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
            'staffCount',
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
            'lastTransactionUpdateTime'
        ));
    }

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
