<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Department;
use App\Models\Supply;
use App\Models\RisSlip;
use App\Models\SupplyStock;
use App\Models\RisItem;
use Illuminate\Support\Facades\DB;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. force‑change‑password banner
        $forceChangePassword = Hash::check('12345678', $user->password);

        // 2. dropdown data for the modal
        $departments = Department::orderBy('name')->get(['id','name']);

        // 3. calculate real-time available for each stock
        $stocks = SupplyStock::with('supply')
            ->where('status', 'available')
            ->where('quantity_on_hand', '>', 0)
            ->get();

        foreach ($stocks as $stock) {
            $pendingRequested = RisItem::where('supply_id', $stock->supply_id)
                ->whereHas('risSlip', function($q) {
                    // count BOTH draft and approved slips as “reserved”
                    $q->whereIn('status', ['draft','approved']);
                })
                ->sum('quantity_requested');

            $stock->available_for_request = max(0, $stock->quantity_on_hand - $pendingRequested);
        }

        // 3. the staff member's own slips with pagination
        $myRequests = RisSlip::where('requested_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'requests');

        // 4. Get received supplies with pagination
        $risNumbers = DB::table('supply_transactions')
            ->join('user_transactions', 'supply_transactions.transaction_id', '=', 'user_transactions.transaction_id')
            ->where('user_transactions.user_id', Auth::id())
            ->where('user_transactions.role', 'receiver')
            ->select('reference_no')
            ->distinct()
            ->pluck('reference_no')
            ->toArray();

        $receivedRequisitions = RisSlip::whereIn('ris_no', $risNumbers)
            ->with('requester')
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'received');

        // 5. Get user properties with pagination
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
            'properties'
        ));
    }

    public function showChangePasswordForm()
    {
        return view('auth.force-change-password');
    }

    public function updatePassword(Request $request)
    {
        // Validate new password
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        // Hash and save
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('staff.dashboard')->with('success', 'Password changed successfully!');
    }
}
