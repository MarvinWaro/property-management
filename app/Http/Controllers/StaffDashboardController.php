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

class StaffDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. force‑change‑password banner
        $forceChangePassword = Hash::check('12345678', $user->password);

        // 2. dropdown data for the modal
        $departments = Department::orderBy('name')->get(['id','name']);
        $stocks = SupplyStock::with('supply')
            ->where('status', 'available')
            ->where('quantity_on_hand', '>', 0)
            ->get();

        // 3. the staff member's own slips
        $myRequests = RisSlip::where('requested_by', $user->id)
            ->latest('ris_date')
            ->get();

        return view('staff-dashboard', compact(
            'forceChangePassword',
            'departments',
            'stocks', // Changed from 'supplies'
            'myRequests'
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
