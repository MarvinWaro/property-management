<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Location;
use App\Models\User;

class DashboardController extends Controller
{
    public function dashboard()
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

        return view('dashboard', compact('totalUsers', 'lastUpdated', 'totalProperties', 'totalLocations'));
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
