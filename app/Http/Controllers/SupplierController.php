<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get the search term from the request
        $search = $request->get('search');

        // Fetch suppliers from the database with optional search functionality
        $suppliers = Supplier::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('contact_number', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('manage-supplier.index', compact('suppliers'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('manage-supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the form inputs
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',                         // 'name' is required
            'email' => 'nullable|email|max:255|unique:suppliers,email',  // 'email' is optional (nullable)
            'contact_no' => 'required|string|max:15|unique:suppliers,contact_number', // 'contact_no' must be unique
        ]);

        // Store the validated data into the database
        Supplier::create([
            'name' => $validatedData['name'],              // Store supplier's name
            'email' => $validatedData['email'],            // Store email (even if it's null)
            'contact_number' => $validatedData['contact_no'], // Store contact number
        ]);

        // Redirect back to the index with a success message
        return redirect()->route('supplier.index')->with('success', 'Supplier added successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('manage-supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:suppliers,email,' . $supplier->id,
            'contact_no' => 'required|string|max:15|unique:suppliers,contact_number,' . $supplier->id,
        ]);

        $supplier->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'contact_number' => $validatedData['contact_no'],
        ]);

        return redirect()->route('supplier.index')->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('supplier.index')->with('success', 'Supplier deleted successfully.');
    }

}
