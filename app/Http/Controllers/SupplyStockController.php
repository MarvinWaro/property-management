<?php

namespace App\Http\Controllers;

use App\Models\SupplyStock;
use Illuminate\Http\Request;

class SupplyStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
/**
 * Display a listing of the resource.
 */
    public function index(Request $request)
    {
        // Get search and filter parameters
        $search = $request->get('search');
        $status = $request->get('status');

        // Query with relationships
        $stocksQuery = SupplyStock::with('supply');

        // Apply search filter if provided
        if ($search) {
            $stocksQuery->whereHas('supply', function ($query) use ($search) {
                $query->where('item_name', 'like', "%{$search}%")
                    ->orWhere('stock_no', 'like', "%{$search}%");
            });
        }

        // Apply status filter if provided
        if ($status) {
            $stocksQuery->where('status', $status);
        }

        // Get supplies for adding new stock
        $supplies = \App\Models\Supply::all();

        // Execute the query and paginate
        $stocks = $stocksQuery->paginate(10);

        return view('manage-stock.index', compact('stocks', 'supplies'));
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->merge([
                'unit_cost' => $request->unit_cost ? str_replace(',', '', $request->unit_cost) : '0.00',
            ]);

            $validated = $request->validate([
                'supply_id' => 'required|exists:supplies,supply_id',
                'quantity_on_hand' => 'required|integer|min:0',
                'unit_cost' => 'required|numeric|min:0',
                'expiry_date' => 'nullable|date',
                'status' => 'required|in:available,reserved,expired,depleted',
                'fund_cluster' => 'nullable|string|max:255',
                'days_to_consume' => 'nullable|integer|min:0',
                'remarks' => 'nullable|string',
            ]);

            // Calculate total cost
            $validated['total_cost'] = $validated['quantity_on_hand'] * $validated['unit_cost'];

            SupplyStock::create($validated);

            return redirect()->route('stocks.index')->with('success', 'Stock added successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('stocks.index')
                ->withErrors($e->validator)
                ->withInput()
                ->with('show_create_modal', true);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->merge([
                'unit_cost' => $request->unit_cost ? str_replace(',', '', $request->unit_cost) : '0.00',
            ]);

            $stock = SupplyStock::findOrFail($id);

            $validated = $request->validate([
                'quantity_on_hand' => 'required|integer|min:0',
                'unit_cost' => 'required|numeric|min:0',
                'expiry_date' => 'nullable|date',
                'status' => 'required|in:available,reserved,expired,depleted',
                'fund_cluster' => 'nullable|string|max:255',
                'days_to_consume' => 'nullable|integer|min:0',
                'remarks' => 'nullable|string',
            ]);

            // Calculate total cost
            $validated['total_cost'] = $validated['quantity_on_hand'] * $validated['unit_cost'];

            $stock->update($validated);

            return redirect()->route('stocks.index')->with('success', 'Stock updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('stocks.index')
                ->withErrors($e->validator)
                ->withInput()
                ->with('show_edit_modal', $id);
        } catch (\Exception $e) {
            return redirect()->route('stocks.index')->with('error', 'Error updating stock: ' . $e->getMessage());
        }
    }

    public function destroy(SupplyStock $supplyStock)
    {
        //
    }
}
