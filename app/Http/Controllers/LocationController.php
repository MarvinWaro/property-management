<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        // Get the search term from the request
        $search = $request->get('search');

        // Fetch locations that are not excluded
        $locations = Location::where('excluded', 0)
            ->when($search, function ($query, $search) {
                return $query->where('location_name', 'like', "%{$search}%");
            })
            ->paginate(5);

        return view('manage-location.index', compact('locations'));
    }

    public function create()
    {
        return view('manage-location.create');
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'location_name' => 'required|string|max:255',
        ]);

        // Check if an ACTIVE location exists
        $existingLocation = Location::where('excluded', 0)
            ->where('location_name', $request->location_name)
            ->first();

        if ($existingLocation) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['location_name' => 'Location name is already taken.']);
        }

        // Find the first EXCLUDED location and reuse it
        $excludedLocation = Location::where('excluded', 1)->first();

        if ($excludedLocation) {
            // Reactivate the excluded location with new details
            $excludedLocation->update([
                'location_name' => $request->location_name,
                'excluded' => 0, // Mark as active
                'active' => 1,
                'updated_at' => now(),
            ]);

            return redirect()->route('location.index')->with('success', 'Location added successfully.');
        }

        // If no excluded location is found, create a new location
        Location::create([
            'location_name' => $request->location_name,
            'active' => 1,
            'excluded' => 0
        ]);

        return redirect()->route('location.index')->with('success', 'Location created successfully.');
    }

    public function edit(Location $location)
    {
        return view('manage-location.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        // Validate input (no regex, just max length and unique rule)
        $request->validate([
            'location_name' => 'required|string|max:255|unique:locations,location_name,' . $location->id,
        ]);

        // Update location
        $location->update([
            'location_name' => $request->location_name,
        ]);

        return redirect()->route('location.index')->with('success', 'Location updated successfully.');
    }

    public function destroy(Location $location)
    {
        // Check if the location is already excluded
        if ($location->excluded) {
            return redirect()->route('location.index')->with('error', 'Location is already excluded.');
        }

        // Mark the location as excluded and inactive
        $location->update([
            'excluded' => 1,
            'active' => 0
        ]);

        return redirect()->route('location.index')->with('deleted', 'Location has been removed.');
    }

}
