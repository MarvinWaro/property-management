<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        // Get the search term from the request
        $search = $request->get('search');

        // Fetch and paginate locations with search filter
        $locations = Location::when($search, function ($query, $search) {
                return $query->where('location_name', 'like', "%{$search}%");
            })
            ->paginate(5); // Paginate with 5 per page

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
            'location_name' => 'required|string|regex:/^[a-zA-Z0-9\s]+$/|unique:locations,location_name|max:255',
        ]);

        // Store new location
        Location::create([
            'location_name' => $request->location_name,
        ]);

        // Redirect with success message
        return redirect()->route('location.index')->with('success', 'Location added successfully.');
    }

    public function edit(Location $location)
    {
        return view('manage-location.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        // Validate input
        $request->validate([
            'location_name' => 'required|string|regex:/^[a-zA-Z0-9\s]+$/|unique:locations,location_name,' . $location->id . '|max:255',
        ]);

        // Update location
        $location->update([
            'location_name' => $request->location_name
        ]);

        return redirect()->route('location.index')->with('success', 'Location updated successfully.');
    }

    public function destroy(string $id)
    {
        //
    }
}
