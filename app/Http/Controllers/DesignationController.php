<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $designations = \App\Models\Designation::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })->paginate(10);

        return view('manage-designation.index', compact('designations'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('manage-designation.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Designation::create($validated);

        return redirect()->route('designations.index')
                        ->with('success', 'Designation created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Designation $designation)
    {
        //
    }


    public function edit(\App\Models\Designation $designation)
    {
        // Return the edit view with the selected designation
        return view('manage-designation.edit', compact('designation'));
    }

    public function update(Request $request, Designation $designation)
    {
        // Validate the input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Update the designation in the database
        $designation->update($validated);

        // Redirect back to the index with a success message
        return redirect()->route('designations.index')->with('success', 'Designation updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Designation $designation)
    {
        $designation->delete();
        return redirect()->route('designations.index')->with('success', 'Designation deleted successfully.');
    }
}
