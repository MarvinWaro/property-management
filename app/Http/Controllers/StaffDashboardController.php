<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $forceChangePassword = false;

        // Check if the currently logged in user still has '12345678' as their password
        if (Hash::check('12345678', Auth::user()->password)) {
            $forceChangePassword = true;
        }

        return view('staff-dashboard', compact('forceChangePassword'));
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
