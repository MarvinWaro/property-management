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
     * Store a newly created receipt in storage,
     * update weighted‑average summary, and log transaction.
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
            // 1) Pull or init the summary row (group only by supply+cluster)
            $stock = SupplyStock::firstOrNew([
                'supply_id'    => $validated['supply_id'],
                'fund_cluster' => $validated['fund_cluster'],
            ]);

            // 2) Compute previous totals
            $oldQty       = $stock->exists ? $stock->quantity_on_hand : 0;
            $oldTotalCost = $stock->exists ? $stock->total_cost       : 0;

            // 3) Add the new lot
            $newQty        = $oldQty + $validated['quantity_on_hand'];
            $newLotCost    = $validated['quantity_on_hand'] * $validated['unit_cost'];
            $newTotalCost  = $oldTotalCost + $newLotCost;
            $newAvgCost    = $newTotalCost / $newQty;

            // 4) Update summary row
            $stock->quantity_on_hand = $newQty;
            $stock->total_cost       = $newTotalCost;
            $stock->unit_cost        = $newAvgCost;
            $stock->expiry_date      = $validated['expiry_date'];
            $stock->status           = $validated['status'];
            $stock->days_to_consume  = $validated['days_to_consume'];
            $stock->remarks          = $validated['remarks'];
            $stock->save();

            // 5) Log this receipt transaction
            SupplyTransaction::create([
                'supply_id'        => $validated['supply_id'],
                'transaction_type' => 'receipt',
                'transaction_date' => now()->toDateString(),
                'reference_no'     => $request->input('reference_no','Manual Stocking'),
                'quantity'         => $validated['quantity_on_hand'],
                'unit_cost'        => $validated['unit_cost'],
                'total_cost'       => $newLotCost,
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
     * Update only metadata on the summary row.
     * We do NOT touch historical lots here.
     */
    public function update(Request $request, $id)
    {
        $request->merge([
            'unit_cost' => $request->unit_cost ? str_replace(',', '', $request->unit_cost) : 0,
        ]);

        $validated = $request->validate([
            // Only allow editing of metadata/valuation fields:
            'unit_cost'        => 'required|numeric|min:0',
            'expiry_date'      => 'nullable|date',
            'status'           => 'required|in:available,reserved,expired,depleted',
            'fund_cluster'     => 'required|in:101,151',
            'days_to_consume'  => 'nullable|integer|min:0',
            'remarks'          => 'nullable|string',
        ]);

        $stock = SupplyStock::findOrFail($id);

        // We keep the same quantity but re‑value at the new unit_cost
        $currentQty          = $stock->quantity_on_hand;
        $validated['total_cost'] = $currentQty * $validated['unit_cost'];

        $stock->update($validated);

        return redirect()
            ->route('stocks.index')
            ->with('success', 'Stock updated successfully.');
    }

    /**
     * Remove the specified summary row (and implicitly its trace is gone;
     * you could also choose to archive rather than delete).
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
