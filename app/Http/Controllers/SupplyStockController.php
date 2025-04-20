<?php

namespace App\Http\Controllers;

use App\Models\SupplyStock;
use App\Models\SupplyTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplyStockController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $stocksQuery = SupplyStock::with('supply');

        if ($search) {
            $stocksQuery->whereHas('supply', fn($q) =>
                $q->where('item_name','like',"%{$search}%")
                  ->orWhere('stock_no','like',"%{$search}%")
            );
        }
        if ($status) {
            $stocksQuery->where('status', $status);
        }

        $supplies = \App\Models\Supply::all();
        $stocks   = $stocksQuery->paginate(10);

        return view('manage-stock.index', compact('stocks','supplies'));
    }

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
            // 1) find or new by the three grouping fields
            $stock = SupplyStock::firstOrNew([
                'supply_id'    => $validated['supply_id'],
                'fund_cluster' => $validated['fund_cluster'],
                'unit_cost'    => $validated['unit_cost'],
            ]);

            // 2) compute new on‑hand and total
            $oldQty = $stock->exists ? $stock->quantity_on_hand : 0;
            $newQty = $oldQty + $validated['quantity_on_hand'];

            // 3) update summary row
            $stock->quantity_on_hand = $newQty;
            $stock->total_cost       = $newQty * $validated['unit_cost'];
            $stock->expiry_date      = $validated['expiry_date'];
            $stock->status           = $validated['status'];
            $stock->days_to_consume  = $validated['days_to_consume'];
            $stock->remarks          = $validated['remarks'];
            // fund_cluster & unit_cost already set by firstOrNew
            $stock->save();

            // 4) log transaction
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
            ->with('success','Stock received and transaction logged.');
    }

    // update() and destroy() unchanged
}
