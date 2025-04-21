<?php

namespace App\Http\Controllers;

use App\Models\SupplyStock;
use App\Models\SupplyTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplyStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $stocksQuery = SupplyStock::with('supply');

        if ($search) {
            $stocksQuery->whereHas('supply', function($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('stock_no',   'like', "%{$search}%");
            });
        }

        if ($status) {
            $stocksQuery->where('status', $status);
        }

        $supplies = \App\Models\Supply::all();
        $stocks   = $stocksQuery->paginate(10);

        return view('manage-stock.index', compact('stocks', 'supplies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supply_id'        => 'required|exists:supplies,supply_id',
            'quantity_on_hand' => 'required|integer|min:1',
            'unit_cost'        => 'required|numeric|min:0',
            'expiry_date'      => 'nullable|date',
            'status'           => 'required|in:available,reserved,expired,depleted',
            'fund_cluster'     => 'required|in:101,151',
            'days_to_consume'  => 'nullable|integer|min:0',
            'remarks'          => 'nullable|string',
        ]);

        DB::transaction(function() use ($validated, $request) {
            // 1) Find or create the stock summary row
            $stock = SupplyStock::firstOrNew([
                'supply_id'    => $validated['supply_id'],
                'fund_cluster' => $validated['fund_cluster'],
                'unit_cost'    => $validated['unit_cost'],
            ]);

            // 2) Compute new on‑hand and total cost
            $oldQty = $stock->exists ? $stock->quantity_on_hand : 0;
            $newQty = $oldQty + $validated['quantity_on_hand'];

            // 3) Update the summary record
            $stock->quantity_on_hand = $newQty;
            $stock->total_cost       = $newQty * $validated['unit_cost'];
            $stock->expiry_date      = $validated['expiry_date'];
            $stock->status           = $validated['status'];
            $stock->days_to_consume  = $validated['days_to_consume'];
            $stock->remarks          = $validated['remarks'];
            $stock->save();

            // 4) Write the transaction log
            SupplyTransaction::create([
                'supply_id'        => $validated['supply_id'],
                'transaction_type' => 'receipt',
                'transaction_date' => now()->toDateString(),
                'reference_no'     => $request->input('reference_no','Manual Stocking'),
                'quantity'         => $validated['quantity_on_hand'],
                'unit_cost'        => $validated['unit_cost'],
                'total_cost'       => $validated['quantity_on_hand'] * $validated['unit_cost'],
                'balance_quantity' => $newQty,
                'department_id'    => auth()->user()->department_id,
                'user_id'          => auth()->id(),
                'remarks'          => $validated['remarks'],
            ]);
        });

        return redirect()
            ->route('stocks.index')
            ->with('success', 'Stock received and transaction logged.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->merge([
            'unit_cost' => $request->unit_cost ? str_replace(',', '', $request->unit_cost) : 0,
        ]);

        $validated = $request->validate([
            // Metadata fields that are allowed to be edited
            'unit_cost'        => 'required|numeric|min:0',
            'expiry_date'      => 'nullable|date',
            'status'           => 'required|in:available,reserved,expired,depleted',
            'fund_cluster'     => 'required|in:101,151',
            'days_to_consume'  => 'nullable|integer|min:0',
            'remarks'          => 'nullable|string',
        ]);

        $stock = SupplyStock::findOrFail($id);

        // Keep the existing quantity, only update the validated fields
        $originalQuantity = $stock->quantity_on_hand;

        // Recalculate total cost based on new unit cost and existing quantity
        $validated['total_cost'] = $originalQuantity * $validated['unit_cost'];

        $stock->update($validated);

        return redirect()
            ->route('stocks.index')
            ->with('success', 'Stock updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $stock = SupplyStock::findOrFail($id);
        $stock->delete();

        return redirect()
            ->route('stocks.index')
            ->with('deleted', 'Stock deleted successfully.');
    }
}
