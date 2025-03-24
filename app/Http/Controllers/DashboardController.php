<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Location;
use App\Models\EndUser;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Count active users (where 'excluded' is 0)
        $totalUsers = EndUser::where('excluded', 0)->count();

        // Retrieve the most recently updated employee record
        $lastUpdatedRecord = EndUser::orderBy('updated_at', 'desc')->first();
        $lastUpdated = $lastUpdatedRecord ? $lastUpdatedRecord->updated_at : null;

        // Count active properties (where 'excluded' is 0)
        $totalProperties = Property::where('excluded', 0)->count();

        // Count active locations (where 'excluded' is 0)
        $totalLocations = Location::where('excluded', 0)->count();

        return view('dashboard', compact('totalUsers', 'lastUpdated', 'totalProperties', 'totalLocations'));
    }
}
