<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\Department;
use App\Models\SupplyStock;
use App\Models\RisSlip;
use App\Models\RisItem;

class StaffDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user   = Auth::user();
        $userId = $user->id;

        // 1. Force-change-password banner
        $forceChangePassword = Hash::check('12345678', $user->password);

        // 2. Dropdown data for the modal
        $departments = Department::orderBy('name')->get(['id','name']);

        // 3. Calculate real-time available for each stock
        $stocks = SupplyStock::with('supply')
            ->whereIn('status', ['available', 'depleted'])
            ->get();

        foreach ($stocks as $stock) {
            $pendingRequested = RisItem::where('supply_id', $stock->supply_id)
                ->whereHas('risSlip', fn($q) =>
                    $q->whereIn('status', ['draft','approved'])
                )
                ->sum('quantity_requested');

            $stock->available_for_request = max(
                0,
                $stock->quantity_on_hand - $pendingRequested
            );
        }

        // 4. Stats for the status filters (calculated BEFORE applying filters)
        $totalRequests       = RisSlip::where('requested_by', $userId)->count();
        $pendingCount        = RisSlip::where('requested_by', $userId)
                                  ->where('status', 'draft')
                                  ->count();
        $approvedCount       = RisSlip::where('requested_by', $userId)
                                  ->where('status', 'approved')
                                  ->count();
        $pendingReceiptCount = RisSlip::where('requested_by', $userId)
                                  ->where('status', 'posted')
                                  ->whereNull('received_at')
                                  ->count();
        $completedCount      = RisSlip::where('requested_by', $userId)
                                  ->where('status', 'posted')
                                  ->whereNotNull('received_at')
                                  ->count();

        // 5. Build query for "My Requests" with filters and search
        // Note: Search only happens when form is submitted (search button clicked)
        $query = RisSlip::where('requested_by', $userId)
            ->with(['department', 'items.supply'])
            ->orderBy('created_at', 'desc');

        // Apply status filter if present
        if ($request->filled('status')) {
            match($request->status) {
                'draft'           => $query->where('status', 'draft'),
                'approved'        => $query->where('status', 'approved'),
                'pending-receipt' => $query->where('status','posted')
                                           ->whereNull('received_at'),
                'completed'       => $query->where('status','posted')
                                           ->whereNotNull('received_at'),
                default           => null,
            };
        }

        // Apply search filter if present (only when search form is submitted)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('ris_no', 'like', "%{$search}%");
        }

        // Paginate with preserved query parameters
        $myRequests = $query
            ->paginate(5, ['*'], 'requests')
            ->appends($request->only(['status','search']));

        // 6. Received requisitions (unchanged)
        $risNumbers = DB::table('supply_transactions')
            ->join('user_transactions', 'supply_transactions.transaction_id', '=', 'user_transactions.transaction_id')
            ->where('user_transactions.user_id', $userId)
            ->where('user_transactions.role', 'receiver')
            ->select('reference_no')
            ->distinct()
            ->pluck('reference_no')
            ->toArray();

        $receivedRequisitions = RisSlip::whereIn('ris_no', $risNumbers)
            ->with('requester')
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'received');

        // 7. User properties (unchanged)
        $properties = $user->properties()
            ->with('images')
            ->orderBy('created_at', 'desc')
            ->paginate(6, ['*'], 'properties');

        return view('staff-dashboard', compact(
            'forceChangePassword',
            'departments',
            'stocks',
            'myRequests',
            'receivedRequisitions',
            'properties',
            'totalRequests',
            'pendingCount',
            'approvedCount',
            'pendingReceiptCount',
            'completedCount'
        ));
    }

    public function showChangePasswordForm()
    {
        return view('auth.force-change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()
            ->route('staff.dashboard')
            ->with('success', 'Password changed successfully!');
    }
}
