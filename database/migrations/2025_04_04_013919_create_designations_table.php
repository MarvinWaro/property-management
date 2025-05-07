<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:designations,name',
            ]);

            Designation::create($validated);

            // If this is an AJAX request, return JSON response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Designation created successfully.',
                    'redirect' => route('designations.index')
                ]);
            }

            return redirect()->route('designations.index')
                          ->with('success', 'Designation created successfully.');
        } catch (ValidationException $e) {
            // If this is an AJAX request, return validation errors as JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database exceptions like duplicate entries
            $errorMessage = 'An error occurred while saving the designation.';

            // Check for duplicate entry error (code 23000)
            if ($e->getCode() == 23000) {
                $errorMessage = 'This designation name already exists.';
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['name' => [$errorMessage]]
                ], 422);
            }

            return redirect()->back()
                           ->withInput()
                           ->withErrors(['name' => $errorMessage]);
        } catch (\Exception $e) {
            $errorMessage = 'An unexpected error occurred.';

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['name' => [$errorMessage]]
                ], 500);
            }

            return redirect()->back()
                           ->withInput()
                           ->withErrors(['name' => $errorMessage]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Designation $designation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Designation $designation)
    {
        // Return the edit view with the selected designation
        return view('manage-designation.edit', compact('designation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Designation $designation)
    {
        try {
            // Validate the input - ignore current record on unique check
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:designations,name,'.$designation->id,
            ]);

            // Update the designation in the database
            $designation->update($validated);

            // If this is an AJAX request, return JSON response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Designation updated successfully.',
                    'redirect' => route('designations.index')
                ]);
            }

            // Redirect back to the index with a success message
            return redirect()->route('designations.index')->with('success', 'Designation updated successfully.');
        } catch (ValidationException $e) {
            // If this is an AJAX request, return validation errors as JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database exceptions like duplicate entries
            $errorMessage = 'An error occurred while updating the designation.';

            // Check for duplicate entry error (code 23000)
            if ($e->getCode() == 23000) {
                $errorMessage = 'This designation name already exists.';
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['name' => [$errorMessage]]
                ], 422);
            }

            return redirect()->back()
                           ->withInput()
                           ->withErrors(['name' => $errorMessage]);
        } catch (\Exception $e) {
            $errorMessage = 'An unexpected error occurred.';

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['name' => [$errorMessage]]
                ], 500);
            }

            return redirect()->back()
                           ->withInput()
                           ->withErrors(['name' => $errorMessage]);
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
