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

        // Updated to include the supply relationship and latest transaction for current unit cost
        $stocksQuery = SupplyStock::with([
            'supply',
            'latestTransaction' => function($query) {
                $query->orderBy('transaction_date', 'desc')->orderBy('created_at', 'desc');
            }
        ]);

        if ($search) {
            $stocksQuery->whereHas('supply', function($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                ->orWhere('stock_no',   'like', "%{$search}%");
            });
        }

        // Updated status filtering to handle dynamic status
        if ($status && $status !== 'all') {
            if ($status === 'low_stock') {
                // Filter for items that are at or below reorder point
                $stocksQuery->whereHas('supply', function($q) {
                    $q->whereRaw('supply_stocks.quantity_on_hand <= supplies.reorder_point');
                })->where('quantity_on_hand', '>', 0)
                ->whereNotIn('status', ['depleted', 'expired']);
            } elseif ($status === 'depleted') {
                // Items with 0 quantity or manually marked as depleted
                $stocksQuery->where(function($q) {
                    $q->where('quantity_on_hand', '<=', 0)
                    ->orWhere('status', 'depleted');
                });
            } else {
                // For other statuses (available, reserved, expired)
                if ($status === 'available') {
                    // Available means > reorder point and not expired/depleted
                    $stocksQuery->where('quantity_on_hand', '>', 0)
                            ->whereNotIn('status', ['depleted', 'expired'])
                            ->whereHas('supply', function($q) {
                                $q->whereRaw('supply_stocks.quantity_on_hand > supplies.reorder_point');
                            });
                } else {
                    $stocksQuery->where('status', $status);
                }
            }
        }

        $supplies = Supply::all();
        $stocks   = $stocksQuery->paginate(5);

        return view('manage-stock.index', compact('stocks', 'supplies'));
    }

    /**
     * Store a newly created receipt in storage,
     * update weighted‑average summary, and log transaction.
     * Now supports both single item and multiple items
     */
    public function store(Request $request)
    {
        // DOUBLE SUBMISSION PREVENTION - START
        if (!$request->has('submission_token')) {
            $request->merge(['submission_token' => uniqid() . time()]);
        }

        $submissionToken = $request->input('submission_token');
        $sessionKey = 'last_stock_submission_' . auth()->id();

        if (session()->has($sessionKey) && session($sessionKey) === $submissionToken) {
            return back()->with('error', 'Duplicate submission detected. Stock was already saved.');
        }

        session([$sessionKey => $submissionToken]);
        // DOUBLE SUBMISSION PREVENTION - END

        // Check if this is a multiple items submission
        if ($request->has('items') && is_array($request->input('items'))) {
            return $this->storeMultipleItems($request);
        }

        // Single item submission (existing logic)
        // Format the unit cost properly (remove commas)
        $request->merge([
            'unit_cost' => $request->unit_cost ? str_replace(',', '', $request->unit_cost) : 0,
        ]);

        $validated = $request->validate([
            'supply_id'        => 'required|exists:supplies,supply_id',
            'quantity_on_hand' => 'required|integer|min:1',
            'unit_cost'        => 'required|numeric|min:0',
            'expiry_date'      => 'nullable|date',
            'status'           => 'required|in:available,reserved,expired,depleted',
            'fund_cluster'     => 'required|in:101,151',
            'days_to_consume'  => 'nullable|integer|min:0',
            'remarks'          => 'nullable|string',
            'submission_token' => 'required|string',
        ]);

        try {
            // Generate IAR reference number
            $referenceNo = $this->referenceNumberService->generateIarNumber($validated['supply_id']);

            DB::transaction(function() use ($validated, $request, $referenceNo) {
                // Process single item (existing logic remains the same)
                $this->processStockReceipt($validated, $referenceNo);
            });

            // Clear the submission token after successful creation
            session()->forget($sessionKey);

            return redirect()
                ->route('stocks.index')
                ->with('success', 'Stock received and transaction logged.');

        } catch (\Exception $e) {
            // Clear the submission token on error so user can retry
            session()->forget($sessionKey);

            Log::error('Failed to create stock', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'supply_id' => $validated['supply_id'] ?? null,
                'user_id' => auth()->id()
            ]);

            // Show the actual error message for debugging (can be removed in production)
            return back()->with('error', 'Failed to add stock: ' . $e->getMessage());
        }
    }

    /**
     * Handle multiple items submission with single IAR
     */
    private function storeMultipleItems(Request $request)
    {
        // Validate general information and items array
        $request->validate([
            'general_remarks' => 'nullable|string|max:255',
            'submission_token' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.supply_id' => 'required|exists:supplies,supply_id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|string', // String because of formatting
            'items.*.fund_cluster' => 'required|in:101,151',
            'items.*.status' => 'required|in:available,reserved,expired,depleted',
            'items.*.expiry_date' => 'nullable|date',
        ]);

        try {
            // Generate single IAR reference number for all items
            // Pass the first supply_id to maintain compatibility
            $firstSupplyId = $request->input('items')[0]['supply_id'];
            $referenceNo = $this->referenceNumberService->generateIarNumber($firstSupplyId);

            DB::transaction(function() use ($request, $referenceNo) {
                $items = $request->input('items');
                $generalRemarks = $request->input('general_remarks', '');

                foreach ($items as $item) {
                    // Clean up unit cost (remove commas)
                    $item['unit_cost'] = str_replace(',', '', $item['unit_cost']);

                    // Prepare data in the format expected by processStockReceipt
                    $itemData = [
                        'supply_id' => $item['supply_id'],
                        'quantity_on_hand' => $item['quantity'],
                        'unit_cost' => $item['unit_cost'],
                        'expiry_date' => $item['expiry_date'] ?? null,
                        'status' => $item['status'],
                        'fund_cluster' => $item['fund_cluster'],
                        'days_to_consume' => null,
                        'remarks' => $generalRemarks,
                    ];

                    // Process each item using the same logic
                    $this->processStockReceipt($itemData, $referenceNo);
                }
            });

            // Clear the submission token after successful creation
            session()->forget('last_stock_submission_' . auth()->id());

            // Count items for success message
            $itemCount = count($request->input('items'));
            $itemText = $itemCount > 1 ? $itemCount . ' items' : '1 item';

            return redirect()
                ->route('stocks.index')
                ->with('success', "IAR {$referenceNo} created successfully with {$itemText}.");

        } catch (\Exception $e) {
            // Clear the submission token on error so user can retry
            session()->forget('last_stock_submission_' . auth()->id());

            Log::error('Failed to create stock IAR', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id()
            ]);

            return back()->with('error', 'Failed to add stock: ' . $e->getMessage());
        }
    }

    /**
     * Process a single stock receipt item
     * Extracted to avoid code duplication
     */
    private function processStockReceipt($itemData, $referenceNo)
    {
        // 1) Pull or init the summary row (group only by supply+cluster)
        $stock = SupplyStock::firstOrNew([
            'supply_id'    => $itemData['supply_id'],
            'fund_cluster' => $itemData['fund_cluster'],
        ]);

        // 2) Get current values (before adding new stock)
        $oldQty = $stock->exists ? $stock->quantity_on_hand : 0;
        $oldTotalCost = $stock->exists ? $stock->total_cost : 0;

        // 3) Calculate new lot values
        $newLotQty = $itemData['quantity_on_hand'];
        $newLotUnitCost = $itemData['unit_cost'];
        $newLotTotalCost = $newLotQty * $newLotUnitCost;

        // 4) Calculate new totals after adding the lot
        $newTotalQty = $oldQty + $newLotQty;
        $newTotalCost = $oldTotalCost + $newLotTotalCost;

        // 5) Calculate weighted average unit cost
        $newWeightedAvgCost = $newTotalQty > 0 ? $newTotalCost / $newTotalQty : 0;

        // 6) Update summary row with weighted average
        $stock->quantity_on_hand = $newTotalQty;
        $stock->total_cost = $newTotalCost;
        $stock->unit_cost = round($newWeightedAvgCost, 2); // Round to 2 decimal places
        $stock->expiry_date = $itemData['expiry_date'];
        $stock->status = $itemData['status'];
        $stock->days_to_consume = $itemData['days_to_consume'] ?? null;
        $stock->remarks = $itemData['remarks'];
        $stock->save();

        // 7) Log this receipt transaction with the ORIGINAL unit cost (not weighted average)
        $departmentId = auth()->user()->department_id ?? 1; // Use a default department ID if null

        SupplyTransaction::create([
            'supply_id'        => $itemData['supply_id'],
            'transaction_type' => 'receipt',
            'transaction_date' => now()->toDateString(),
            'reference_no'     => $referenceNo,
            'quantity'         => $newLotQty,
            'unit_cost'        => $newLotUnitCost, // Original cost, not weighted average
            'total_cost'       => $newLotTotalCost,
            'balance_quantity' => $newTotalQty,
            'balance_unit_cost' => round($newWeightedAvgCost, 2),
            'balance_total_cost' => $newTotalCost,
            'department_id'    => $departmentId,
            'user_id'          => auth()->id(),
            'remarks'          => $itemData['remarks'] ?? 'Stock receipt via IAR ' . $referenceNo,
            'fund_cluster'     => $itemData['fund_cluster'],
            'days_to_consume'  => $itemData['days_to_consume'] ?? null,
        ]);

        // Debug logging to track the calculation
        Log::info('Stock Receipt Added', [
            'iar_reference' => $referenceNo,
            'supply_id' => $itemData['supply_id'],
            'old_qty' => $oldQty,
            'old_total_cost' => $oldTotalCost,
            'new_lot_qty' => $newLotQty,
            'new_lot_unit_cost' => $newLotUnitCost,
            'new_lot_total_cost' => $newLotTotalCost,
            'new_total_qty' => $newTotalQty,
            'new_total_cost' => $newTotalCost,
            'new_weighted_avg_cost' => $newWeightedAvgCost,
            'final_unit_cost' => $stock->unit_cost
        ]);
    }

    /**
     * Handle issue transactions (reduce stock)
     */
    public function issue(Request $request)
    {
        $validated = $request->validate([
            'supply_id'       => 'required|exists:supplies,supply_id',
            'quantity'        => 'required|integer|min:1',
            'fund_cluster'    => 'required|in:101,151',
            'reference_no'    => 'required|string',
            'remarks'         => 'nullable|string',
        ]);

        try {
            DB::transaction(function() use ($validated) {
                // Get the current stock
                $stock = SupplyStock::where('supply_id', $validated['supply_id'])
                    ->where('fund_cluster', $validated['fund_cluster'])
                    ->firstOrFail();

                // Check if sufficient stock available
                if ($stock->quantity_on_hand < $validated['quantity']) {
                    throw new \Exception('Insufficient stock available');
                }

                // Calculate new quantities after issue
                $oldQty = $stock->quantity_on_hand;
                $oldTotalCost = $stock->total_cost;
                $issueQty = $validated['quantity'];

                // Use current weighted average cost for issue
                $currentUnitCost = $stock->unit_cost;
                $issueTotalCost = $issueQty * $currentUnitCost;

                // Calculate new balance
                $newQty = $oldQty - $issueQty;
                $newTotalCost = $oldTotalCost - $issueTotalCost;

                // Weighted average remains the same (no new purchases)
                $newWeightedAvgCost = $newQty > 0 ? $stock->unit_cost : 0;

                // Update stock
                $stock->quantity_on_hand = $newQty;
                $stock->total_cost = $newTotalCost;
                $stock->unit_cost = $newWeightedAvgCost;
                $stock->save();

                // Get the authenticated user's department_id or default to a system department
                $departmentId = auth()->user()->department_id ?? 1; // Use a default department ID if null

                // Log the issue transaction
                SupplyTransaction::create([
                    'supply_id'        => $validated['supply_id'],
                    'transaction_type' => 'issue',
                    'transaction_date' => now()->toDateString(),
                    'reference_no'     => $validated['reference_no'],
                    'quantity'         => $issueQty,
                    'unit_cost'        => $currentUnitCost,
                    'total_cost'       => $issueTotalCost,
                    'balance_quantity' => $newQty,
                    'balance_unit_cost' => $newWeightedAvgCost,
                    'balance_total_cost' => $newTotalCost,
                    'department_id'    => $departmentId,
                    'user_id'          => auth()->id(),
                    'remarks'          => $validated['remarks'] ?? 'Stock issued',
                    'fund_cluster'     => $validated['fund_cluster'],
                ]);
            });

            return redirect()
                ->route('stocks.index')
                ->with('success', 'Stock issued successfully.');

        } catch (\Exception $e) {
            Log::error('Failed to issue stock', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'supply_id' => $validated['supply_id'] ?? null,
                'user_id' => auth()->id()
            ]);

            return back()->with('error', 'Failed to issue stock: ' . $e->getMessage());
        }
    }

    /**
     * Update only metadata on the summary row.
     * We do NOT touch historical lots here.
     */
    public function update(Request $request, $id)
    {
        // DOUBLE SUBMISSION PREVENTION - START
        if (!$request->has('submission_token')) {
            $request->merge(['submission_token' => uniqid() . time()]);
        }

        $submissionToken = $request->input('submission_token');
        $sessionKey = 'last_stock_update_' . auth()->id() . '_' . $id;

        if (session()->has($sessionKey) && session($sessionKey) === $submissionToken) {
            return back()->with('error', 'Duplicate submission detected. Stock was already updated.');
        }

        session([$sessionKey => $submissionToken]);
        // DOUBLE SUBMISSION PREVENTION - END

        $request->merge([
            'unit_cost' => $request->unit_cost ? str_replace(',', '', $request->unit_cost) : 0,
        ]);

        $validated = $request->validate([
            'unit_cost'        => 'required|numeric|min:0',
            'expiry_date'      => 'nullable|date',
            'status'           => 'required|in:available,reserved,expired,depleted',
            'fund_cluster'     => 'required|in:101,151',
            'days_to_consume'  => 'nullable|integer|min:0',
            'remarks'          => 'nullable|string',
            'submission_token' => 'required|string',
        ]);

        try {
            $stock = SupplyStock::findOrFail($id);

            // We keep the same quantity but re‑value at the new unit_cost
            $currentQty = $stock->quantity_on_hand;
            $validated['total_cost'] = $currentQty * $validated['unit_cost'];

            // Update stock with all validated fields, including remarks
            $stock->update($validated);

                // Get the authenticated user's department_id or default to a system department
                $departmentId = auth()->user()->department_id ?? 1; // Use a default department ID if null

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
                    'balance_unit_cost' => $validated['unit_cost'],
                    'balance_total_cost' => $validated['total_cost'],
                    'department_id'    => $departmentId, // Use the checked department ID
                    'user_id'          => auth()->id(),
                    'remarks'          => $validated['remarks'] ?? 'Stock re-valued',
                    'fund_cluster'     => $validated['fund_cluster'],
                    'days_to_consume'  => $validated['days_to_consume'],
                ]);

            session()->forget($sessionKey);

            return redirect()
                ->route('stocks.index')
                ->with('success', 'Stock updated successfully.');

        } catch (\Exception $e) {
            session()->forget($sessionKey);

            Log::error('Failed to update stock', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'stock_id' => $id,
                'user_id' => auth()->id()
            ]);

            return back()->with('error', 'Failed to update stock: ' . $e->getMessage());
        }
    }

    /**
     * Create beginning balance entries for the new year
     */
    public function createBeginningBalances()
    {
        $sessionKey = 'beginning_balance_created_' . auth()->id();

        if (session()->has($sessionKey) && session($sessionKey) === now()->format('Y-m-d')) {
            return redirect()->route('stocks.index')
                ->with('error', 'Beginning balances have already been created today.');
        }

        if (!$this->referenceNumberService->isNewYear()) {
            return redirect()->route('stocks.index')
                ->with('error', 'Beginning balance can only be created at the beginning of the year.');
        }

        try {
            $stocks = SupplyStock::where('quantity_on_hand', '>', 0)->get();

            DB::transaction(function() use ($stocks) {
                foreach ($stocks as $stock) {
                // Get the authenticated user's department_id or default to a system department
                $departmentId = auth()->user()->department_id ?? 1; // Use a default department ID if null

                SupplyTransaction::create([
                    'supply_id'        => $stock->supply_id,
                    'transaction_type' => 'receipt',
                    'transaction_date' => now()->startOfYear()->toDateString(),
                    'reference_no'     => 'Beginning Balance',
                    'quantity'         => $stock->quantity_on_hand,
                    'unit_cost'        => $stock->unit_cost,
                    'total_cost'       => $stock->total_cost,
                    'balance_quantity' => $stock->quantity_on_hand,
                    'balance_unit_cost' => $stock->unit_cost,
                    'balance_total_cost' => $stock->total_cost,
                    'department_id'    => $departmentId, // Use the checked department ID
                    'user_id'          => auth()->id(),
                    'remarks'          => 'Beginning balance for ' . now()->year,
                    'fund_cluster'     => $stock->fund_cluster,
                ]);
                }
            });

            session([$sessionKey => now()->format('Y-m-d')]);

            return redirect()->route('stocks.index')
                ->with('success', 'Beginning balances created successfully for ' . now()->year);

        } catch (\Exception $e) {
            Log::error('Failed to create beginning balances', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id()
            ]);

            return redirect()->route('stocks.index')
                ->with('error', 'Failed to create beginning balances: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified summary row
     */
    public function destroy($id)
    {
        try {
            $stock = SupplyStock::findOrFail($id);

            Log::info('Stock deleted', [
                'stock_id' => $stock->stock_id,
                'supply_id' => $stock->supply_id,
                'quantity' => $stock->quantity_on_hand,
                'user_id' => auth()->id()
            ]);

            $stock->delete();

            return redirect()
                ->route('stocks.index')
                ->with('deleted', 'Stock deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Failed to delete stock', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'stock_id' => $id,
                'user_id' => auth()->id()
            ]);

            return redirect()
                ->route('stocks.index')
                ->with('error', 'Failed to delete stock: ' . $e->getMessage());
        }
    }
}
