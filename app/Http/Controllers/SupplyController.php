<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\SupplyStock;
use App\Models\Category;
use Illuminate\Http\Request;

class SupplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Only fetch categories for the dropdown
        $categories = Category::all();

        // Start building the query for supplies with related category only
        $suppliesQuery = Supply::with(['category']);

        // Check if a search term was provided
        if ($search = $request->input('search')) {
            $suppliesQuery->where(function ($query) use ($search) {
                $query->where('stock_no', 'like', "%{$search}%")
                    ->orWhere('item_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Execute the query to get supplies with pagination
        $supplies = $suppliesQuery->paginate(25);

        // Pass only categories and supplies to your view
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
                'category_id'         => 'nullable|exists:categories,id',
                'reorder_point'       => 'required|integer|min:0',
                'acquisition_cost'    => 'nullable|numeric',
            ]);

            $supply = Supply::create(array_merge($validated, ['is_active' => true]));

            // Auto-create a default supply_stock record so the supply appears in the stocks listing
            SupplyStock::create([
                'supply_id'        => $supply->supply_id,
                'quantity_on_hand' => 0,
                'unit_cost'        => 0,
                'total_cost'       => 0,
                'status'           => 'depleted',
            ]);

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
                'category_id'         => 'nullable|exists:categories,id',
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
