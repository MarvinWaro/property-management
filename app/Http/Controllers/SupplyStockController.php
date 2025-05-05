<?php

namespace App\Http\Controllers;

use App\Models\SupplyStock;
use App\Models\SupplyTransaction;
use App\Models\Supply;
use App\Services\ReferenceNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SupplyStockController extends Controller
{
    /**
     * @var ReferenceNumberService
     */
    protected $referenceNumberService;

    public function __construct(ReferenceNumberService $referenceNumberService)
    {
        $this->referenceNumberService = $referenceNumberService;
    }

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

        $supplies = Supply::all();
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

        // Generate IAR reference number
        $referenceNo = $this->referenceNumberService->generateIarNumber($validated['supply_id']);

        DB::transaction(function() use ($validated, $request, $referenceNo) {
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
                'reference_no'     => $referenceNo,
                'quantity'         => $validated['quantity_on_hand'],
                'unit_cost'        => $validated['unit_cost'],
                'total_cost'       => $newLotCost,
                'balance_quantity' => $newQty,
                'department_id'    => auth()->user()->department_id,
                'user_id'          => auth()->id(),
                'remarks'          => $validated['remarks'],
                'fund_cluster'     => $validated['fund_cluster'],
                'days_to_consume'  => $validated['days_to_consume'],
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
        // Log the incoming data for debugging
        Log::debug('Update stock request data:', $request->all());

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

        // Log the validated data for debugging
        Log::debug('Validated update data:', $validated);

        $stock = SupplyStock::findOrFail($id);

        // We keep the same quantity but re‑value at the new unit_cost
        $currentQty = $stock->quantity_on_hand;
        $validated['total_cost'] = $currentQty * $validated['unit_cost'];

        // Update stock with all validated fields, including remarks
        $stock->update($validated);

        // Create an adjustment transaction to log this change
        SupplyTransaction::create([
            'supply_id'        => $stock->supply_id,
            'transaction_type' => 'adjustment',
            'transaction_date' => now()->toDateString(),
            'reference_no'     => 'Re-valuation',
            'quantity'         => 0, // Zero quantity for re-valuation
            'unit_cost'        => $validated['unit_cost'],
            'total_cost'       => 0, // Zero cost impact
            'balance_quantity' => $stock->quantity_on_hand,
            'department_id'    => auth()->user()->department_id,
            'user_id'          => auth()->id(),
            'remarks'          => $validated['remarks'] ?? 'Stock re-valued', // Make sure we capture remarks
            'fund_cluster'     => $validated['fund_cluster'],
            'days_to_consume'  => $validated['days_to_consume'],
        ]);

        return redirect()
            ->route('stocks.index')
            ->with('success', 'Stock updated successfully.');
    }

    /**
     * Create beginning balance entries for the new year
     */
    public function createBeginningBalances()
    {
        // Check if we're in a new year period
        if (!$this->referenceNumberService->isNewYear()) {
            return redirect()->route('stocks.index')
                ->with('error', 'Beginning balance can only be created at the beginning of the year.');
        }

        // Get all supplies with stock
        $stocks = SupplyStock::where('quantity_on_hand', '>', 0)->get();

        DB::transaction(function() use ($stocks) {
            foreach ($stocks as $stock) {
                // Create beginning balance transaction for each stock
                SupplyTransaction::create([
                    'supply_id'        => $stock->supply_id,
                    'transaction_type' => 'receipt',
                    'transaction_date' => now()->startOfYear()->toDateString(),
                    'reference_no'     => 'Beginning Balance',
                    'quantity'         => $stock->quantity_on_hand,
                    'unit_cost'        => $stock->unit_cost,
                    'total_cost'       => $stock->total_cost,
                    'balance_quantity' => $stock->quantity_on_hand,
                    'department_id'    => auth()->user()->department_id,
                    'user_id'          => auth()->id(),
                    'remarks'          => 'Beginning balance for ' . now()->year,
                    'fund_cluster'     => $stock->fund_cluster,
                ]);
            }
        });

        return redirect()->route('stocks.index')
            ->with('success', 'Beginning balances created successfully for ' . now()->year);
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
