<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Location;
use App\Models\EndUser;

class PropertyController extends Controller
{

    public function index()
    {
        // Fetch all properties from the database
        $properties = Property::paginate(10);

        // Pass properties to the view
        return view('manage-property.index', compact('properties'));
    }

    public function create()
    {
        // Assuming you use excluded=0 for active items (if not, just remove `->where('excluded', 0)`):
        $locations = Location::all();
        $endUsers = EndUser::all();

        return view('manage-property.create', compact('locations', 'endUsers'));
    }

    public function store(Request $request)
    {
        // Validate the form fields
        $request->validate([
            'item_name'                   => 'required|string|max:255',
            'item_description'            => 'nullable|string',
            'serial_no'                   => 'nullable|string|unique:properties,serial_no',
            'model_no'                    => 'nullable|string',
            'acquisition_date'            => 'nullable|date',
            'acquisition_cost'            => 'nullable|numeric',
            'unit_of_measure'             => 'nullable|string|max:50',
            'quantity_per_physical_count' => 'required|integer|min:1',
            'fund'                        => 'required|string',
            'location_id'                 => 'required|exists:locations,id',
            'end_user_id'                 => 'required|exists:end_users,id',
            'condition'                   => 'required|string',
            'remarks'                     => 'nullable|string',
        ]);

        // Create a new Property entry
        Property::create($request->all());

        // Redirect to the property list (index) with a success message
        return redirect()
            ->route('property.index')
            ->with('success', 'Property created successfully!');
    }



}
