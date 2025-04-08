<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Location;
use App\Models\User;
use App\Models\Department;
use App\Models\Designation;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        // Get search query if exists
        $search = $request->get('search');

        // Stats for the page
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
            ->get();

        // For the create form
        $departments = Department::all();
        $designations = Designation::all();

        return view('dashboard', compact(
            'totalUsers',
            'lastUpdated',
            'totalProperties',
            'totalLocations',
            'users',
            'departments',
            'designations',
            'search' // Pass the search query to the view
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
