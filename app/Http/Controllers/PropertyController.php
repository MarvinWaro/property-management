<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Location;
use App\Models\EndUser;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->get('search');


        $properties = Property::where('excluded', 0)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    // Search multiple columns in the 'properties' table
                    $q->where('item_name', 'like', "%{$search}%")
                        ->orWhere('item_description', 'like', "%{$search}%")
                        ->orWhere('serial_no', 'like', "%{$search}%")
                        ->orWhere('model_no', 'like', "%{$search}%")
                        ->orWhere('acquisition_cost', 'like', "%{$search}%")
                        ->orWhere('unit_of_measure', 'like', "%{$search}%")
                        ->orWhere('fund', 'like', "%{$search}%")
                        ->orWhere('condition', 'like', "%{$search}%")
                        ->orWhere('remarks', 'like', "%{$search}%")
                        ->orWhere('quantity_per_physical_count', 'like', "%{$search}%");

                    // Also search in related 'location' model
                    $q->orWhereHas('location', function ($subQ) use ($search) {
                        $subQ->where('location_name', 'like', "%{$search}%");
                    });

                    // Also search in related 'endUser' model
                    $q->orWhereHas('endUser', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('department', 'like', "%{$search}%");
                    });
                });
            })
            ->paginate(5);

        return view('manage-property.index', compact('properties'));
    }

    public function create()
    {
        $locations = Location::where('excluded', 0)->get();
        // Fetch all end users, so that the excluded ones are available.
        $endUsers  = EndUser::all();

        return view('manage-property.create', compact('locations', 'endUsers'));
    }

    public function store(Request $request)
    {
        // Remove commas from acquisition cost and set to null if empty.
        $request->merge([
            'acquisition_cost' => $request->acquisition_cost ? str_replace(',', '', $request->acquisition_cost) : null,
        ]);

        // Validate input.
        $request->validate([
            'property_number'             => 'required|string|unique:properties,property_number,NULL,id,excluded,0',
            'item_name'                   => 'required|string|max:255',
            'item_description'            => 'nullable|string',
            'serial_no'                   => 'nullable|string|unique:properties,serial_no',
            'model_no'                    => 'nullable|string',
            'acquisition_date'            => 'nullable|date',
            'acquisition_cost'            => 'nullable|numeric',
            'unit_of_measure'             => 'nullable|string|max:50',
            'quantity_per_physical_count' => 'required|integer|min:1',
            'fund'                        => 'nullable|string',
            'location_id'                 => 'required|exists:locations,id',
            'end_user_id'                 => 'required|exists:end_users,id',
            'condition'                   => 'required|string',
            'remarks'                     => 'nullable|string',
            'images'                      => 'nullable|array|max:3',
            'images.*'                    => 'image|max:7168',
        ]);

        // Additional uniqueness check for serial_no among active properties.
        if (!empty($request->serial_no)) {
            $existsActive = Property::where('excluded', 0)
                ->where('serial_no', $request->serial_no)
                ->exists();

            if ($existsActive) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['serial_no' => 'Serial number is already taken among active properties.']);
            }
        }

        // Attempt to find any excluded property record.
        $excludedProperty = Property::where('excluded', 1)->first();

        if ($excludedProperty) {
            // Remove old images before reactivating
            foreach ($excludedProperty->images as $image) {
                Storage::disk('public')->delete($image->file_path);
                $image->delete();
            }

            // Reactivate the excluded property with new details.
            $excludedProperty->update([
                'property_number'             => $request->property_number,
                'item_name'                   => $request->item_name,
                'item_description'            => $request->item_description,
                'serial_no'                   => $request->serial_no,
                'model_no'                    => $request->model_no,
                'acquisition_date'            => $request->acquisition_date,
                'acquisition_cost'            => $request->acquisition_cost,
                'unit_of_measure'             => $request->unit_of_measure,
                'quantity_per_physical_count' => $request->quantity_per_physical_count,
                'fund'                        => $request->fund,
                'location_id'                 => $request->location_id,
                'end_user_id'                 => $request->end_user_id,
                'condition'                   => $request->condition,
                'remarks'                     => $request->remarks,
                'excluded'                    => 0,
                'active'                      => 1,
            ]);

            // Process new images if available.
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path     = $image->storeAs('property_images', $filename, 'public');
                    $excludedProperty->images()->create([
                        'file_path' => $path,
                    ]);
                }
            }

            return redirect()->route('property.index')
                ->with('success', 'Property reactivated successfully.');
        }

        // Otherwise, create a brand-new property.
        $property = Property::create([
            'property_number'             => $request->property_number,
            'item_name'                   => $request->item_name,
            'item_description'            => $request->item_description,
            'serial_no'                   => $request->serial_no,
            'model_no'                    => $request->model_no,
            'acquisition_date'            => $request->acquisition_date,
            'acquisition_cost'            => $request->acquisition_cost,
            'unit_of_measure'             => $request->unit_of_measure,
            'quantity_per_physical_count' => $request->quantity_per_physical_count,
            'fund'                        => $request->fund,
            'location_id'                 => $request->location_id,
            'end_user_id'                 => $request->end_user_id,
            'condition'                   => $request->condition,
            'remarks'                     => $request->remarks,
            'active'                      => 1,
            'excluded'                    => 0,
        ]);

        // Process new images if provided.
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path     = $image->storeAs('property_images', $filename, 'public');
                $property->images()->create([
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->route('property.index')
            ->with('success', 'Property created successfully.');
    }


    public function edit(Property $property)
    {
        $locations = Location::where('excluded', 0)->get();
        // Fetch all end users.
        $endUsers  = EndUser::all();

        return view('manage-property.edit', compact('property', 'locations', 'endUsers'));
    }

    public function update(Request $request, Property $property)
    {
        // Remove commas from acquisition cost and set to null if empty.
        $request->merge([
            'acquisition_cost' => $request->acquisition_cost ? str_replace(',', '', $request->acquisition_cost) : null,
        ]);

        // Validate input.
        $request->validate([
            'property_number'             => 'required|string|unique:properties,property_number,' . $property->id,
            'item_name'                   => 'required|string|max:255',
            'item_description'            => 'nullable|string',
            'serial_no'                   => 'nullable|string|unique:properties,serial_no,' . $property->id,
            'model_no'                    => 'nullable|string',
            'acquisition_date'            => 'nullable|date',
            'acquisition_cost'            => 'nullable|numeric',
            'unit_of_measure'             => 'nullable|string|max:50',
            'quantity_per_physical_count' => 'required|integer|min:1',
            'fund'                        => 'nullable|string',
            'location_id'                 => 'required|exists:locations,id',
            'end_user_id'                 => 'required|exists:end_users,id',
            'condition'                   => 'required|string',
            'remarks'                     => 'nullable|string',
            'images'                      => 'nullable|array|max:3',
            'images.*'                    => 'image|max:7168',
        ]);

        // Update property details.
        $property->update([
            'property_number'             => $request->property_number,
            'item_name'                   => $request->item_name,
            'item_description'            => $request->item_description,
            'serial_no'                   => $request->serial_no,
            'model_no'                    => $request->model_no,
            'acquisition_date'            => $request->acquisition_date,
            'acquisition_cost'            => $request->acquisition_cost,
            'unit_of_measure'             => $request->unit_of_measure,
            'quantity_per_physical_count' => $request->quantity_per_physical_count,
            'fund'                        => $request->fund,
            'location_id'                 => $request->location_id,
            'end_user_id'                 => $request->end_user_id,
            'condition'                   => $request->condition,
            'remarks'                     => $request->remarks,
        ]);

        // Check if the user requested to remove existing images.
        if ($request->remove_existing_images == '1') {
            foreach ($property->images as $image) {
                Storage::disk('public')->delete($image->file_path);
                $image->delete();
            }
        }

        // Process new images if provided.
        if ($request->hasFile('images')) {
            // If you already cleared images above, this will simply add the new ones.
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path     = $image->storeAs('property_images', $filename, 'public');
                $property->images()->create([
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->route('property.index')
            ->with('success', 'Property updated successfully!');
    }

    public function destroy(Property $property)
    {
        if ($property->excluded) {
            return redirect()->route('property.index')
                ->with('error', 'Property is already excluded.');
        }

        $property->update([
            'excluded' => 1,
            'active'   => 0
        ]);

        return redirect()->route('property.index')
            ->with('success', 'Property has been removed.');
    }

    public function view(Property $property)
    {
        return view('manage-property.view', compact('property'));
    }
}
