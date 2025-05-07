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
        // Add a form type identifier for validation error handling
        $request->merge(['_form_type' => 'create']);

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:designations,name',
            ]);

            Designation::create($validated);

            return redirect()->route('designations.index')
                          ->with('success', 'Designation created successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Catch database exceptions like duplicate entries
            if ($e->getCode() == 23000) { // Integrity constraint violation
                return redirect()->back()
                                ->withInput()
                                ->withErrors(['name' => 'This designation name already exists.']);
            }

            // For other database errors
            return redirect()->back()
                           ->withInput()
                           ->withErrors(['name' => 'An error occurred while saving the designation.']);
        }
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
        // Add a form type identifier for validation error handling
        $request->merge([
            '_form_type' => 'edit',
            'designation_id' => $designation->id
        ]);

        try {
            // Validate the input - ignore current record on unique check
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:designations,name,'.$designation->id,
            ]);

            // Update the designation in the database
            $designation->update($validated);

            // Redirect back to the index with a success message
            return redirect()->route('designations.index')->with('success', 'Designation updated successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Catch database exceptions
            if ($e->getCode() == 23000) { // Integrity constraint violation
                return redirect()->back()
                                ->withInput()
                                ->withErrors(['name' => 'This designation name already exists.']);
            }

            // For other database errors
            return redirect()->back()
                           ->withInput()
                           ->withErrors(['name' => 'An error occurred while updating the designation.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Designation $designation)
    {
        try {
            $designation->delete();
            return redirect()->route('designations.index')
                           ->with('success', 'Designation deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('designations.index')
                           ->with('error', 'Failed to delete designation. It may be in use.');
        }
    }
}
