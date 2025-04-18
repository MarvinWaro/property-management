<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use Illuminate\Http\Request;
use App\Models\Category; // Make sure to include the Category model

class SupplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Fetch all categories from the database
        $categories = Category::all();

        // Start building the query for supplies with related category
        $suppliesQuery = Supply::with('category');

        // Check if a search term was provided
        if ($search = $request->input('search')) {
            $suppliesQuery->where(function ($query) use ($search) {
                $query->where('stock_no', 'like', "%{$search}%")
                    ->orWhere('item_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Execute the query to get supplies
        $supplies = $suppliesQuery->get();

        // Pass both variables to your view
        return view('supplies.index', compact('categories', 'supplies'));
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            $request->merge([
                'acquisition_cost' => $request->acquisition_cost ? str_replace(',', '', $request->acquisition_cost) : '0.00',
            ]);

            $validated = $request->validate([
                'stock_no'            => 'required|string|unique:supplies,stock_no',
                'item_name'           => 'required|string|max:255',
                'description'         => 'nullable|string',
                'unit_of_measurement' => 'required|string|max:50',
                'category_id'         => 'required|exists:categories,id',
                'reorder_point'       => 'required|integer|min:0',
                'acquisition_cost'    => 'nullable|numeric',
            ]);

            Supply::create(array_merge($validated, ['is_active' => true]));

            return redirect()->route('supplies.index')->with('success', 'Supply created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('supplies.index')
                ->withErrors($e->validator)
                ->withInput()
                ->with('show_create_modal', true); // This flag will be used to reopen the modal
        }
    }

    public function update(Request $request, Supply $supply)
    {
        try {
            $request->merge([
                'acquisition_cost' => $request->acquisition_cost ? str_replace(',', '', $request->acquisition_cost) : '0.00',
            ]);

            $validated = $request->validate([
                'stock_no'            => 'required|string|unique:supplies,stock_no,'.$supply->supply_id.',supply_id',
                'item_name'           => 'required|string|max:255',
                'description'         => 'nullable|string',
                'unit_of_measurement' => 'required|string|max:50',
                'category_id'         => 'required|exists:categories,id',
                'reorder_point'       => 'required|integer|min:0',
                'acquisition_cost'    => 'nullable|numeric',
            ]);

            $supply->update($validated);

            return redirect()->route('supplies.index')->with('success', 'Supply updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('supplies.index')
                ->withErrors($e->validator)
                ->withInput()
                ->with('show_edit_modal', $supply->supply_id); // This flag will be used to reopen the modal
        }
    }


    public function destroy(Supply $supply)
    {
        try {
            $supply->delete();
            return redirect()->route('supplies.index')->with('deleted', 'Supply deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('supplies.index')->with('error', 'Error deleting supply: ' . $e->getMessage());
        }
    }
}
