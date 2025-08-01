<?php

namespace App\Http\Controllers;

use App\Models\SupplyStock;
use App\Models\SupplyTransaction;
use App\Models\Supply;
use App\Models\Supplier;
use App\Models\Department;
use App\Services\ReferenceNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Validator;


class SupplyStockController extends Controller
{
    /**
     * @var ReferenceNumberService
     */
    protected ReferenceNumberService $referenceNumberService;

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

        // Include supply relations + latest transaction for unit cost
        $stocksQuery = SupplyStock::with([
            'supply',
            'supplier',
            'department',
            'latestTransaction' => function($query) {
                $query->orderBy('transaction_date', 'desc')
                    ->orderBy('created_at', 'desc');
            }
        ])
        // Join with supplies table to order by stock_no
        ->join('supplies', 'supply_stocks.supply_id', '=', 'supplies.supply_id')
        ->select('supply_stocks.*') // Select only supply_stocks columns to avoid conflicts
        ->orderByRaw('CAST(supplies.stock_no AS UNSIGNED) ASC'); // Order by stock_no as number in ascending order

        // Text‐search filter
        if ($search) {
            $stocksQuery->whereHas('supply', function($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                ->orWhere('stock_no',   'like', "%{$search}%");
            });
        }

        // Status filter (handles all your dynamic‐status cases)
        if ($status && $status !== 'all') {
            if ($status === 'low_stock') {
                $stocksQuery->whereHas('supply', function($q) {
                    $q->whereRaw('supply_stocks.quantity_on_hand <= supplies.reorder_point');
                })->where('quantity_on_hand', '>', 0)
                ->whereNotIn('status', ['depleted', 'expired']);
            }
            elseif ($status === 'depleted') {
                $stocksQuery->where(function($q) {
                    $q->where('quantity_on_hand', '<=', 0)
                    ->orWhere('status', 'depleted');
                });
            }
            else {
                if ($status === 'available') {
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

        // Fetch selects + pagination
        $supplies    = Supply::all();
        $suppliers   = Supplier::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $stocks      = $stocksQuery->paginate(25);

        // ─── NEW: generate a default IAR for the modal's reference_no input
        //    pick any existing supply_id (we just need an int for the generator)
        $seedSupplyId = optional($supplies->first())->supply_id ?? 1;
        $defaultIar   = $this->referenceNumberService
                            ->generateIarNumber($seedSupplyId);

        // Pass everything (including defaultIar) into the view
        return view('manage-stock.index', compact(
            'stocks',
            'supplies',
            'suppliers',
            'departments',
            'defaultIar'
        ));
    }

    /**
     * AJAX → return the next IAR for a given receipt_date.
     */
    public function nextIar(Request $request)
    {
        $data = $request->validate([
            'receipt_date' => 'required|date',
        ]);

        $date      = Carbon::parse($data['receipt_date']);
        $defaultIar = $this->referenceNumberService
                        ->generateIarNumberForDate($date);

        return response()->json(['defaultIar' => $defaultIar]);
    }



    public function store(Request $request)
    {
        // ── DOUBLE‐SUBMISSION GUARD ──
        if (! $request->has('submission_token')) {
            $request->merge(['submission_token' => uniqid().time()]);
        }
        $token = $request->input('submission_token');
        $sessionKey = 'last_stock_submission_'.auth()->id();
        if (session()->has($sessionKey) && session($sessionKey) === $token) {
            return back()
                ->with('error','Duplicate submission detected.')
                ->withInput()
                ->with('show_create_modal', true);
        }
        session([$sessionKey => $token]);

        // ── MULTI VS SINGLE ──
        if ($request->has('items') && is_array($request->input('items'))) {
            return $this->storeMultipleItems($request);
        }

        // strip commas from unit_cost
        $request->merge([
            'unit_cost' => $request->unit_cost
                ? str_replace(',','',$request->unit_cost)
                : 0,
        ]);

        // ── VALIDATE ──
        $validated = $request->validate([
            'reference_no'        => [
                'required',
                'regex:/^IAR \d{4}-\d{2}-\d{3}$/',
                Rule::unique('supply_transactions','reference_no')
                    ->where('supply_id', $request->input('supply_id')),
            ],
            'receipt_date'        => 'required|date|before_or_equal:today',
            'supply_id'           => 'required|exists:supplies,supply_id',
            'supplier_id'         => 'nullable|exists:suppliers,id',
            'department_id'       => 'nullable|exists:departments,id',
            'quantity_on_hand'    => 'required|integer|min:1',
            'unit_cost'           => 'required|numeric|min:0',
            'expiry_date'         => 'nullable|date',
            'status'              => 'required|in:available,reserved,expired,depleted',
            'fund_cluster'        => 'required|in:101,151',
            'days_to_consume'     => 'nullable|integer|min:0',
            'remarks'             => 'nullable|string',
            'submission_token'    => 'required|string',
        ]);

        try {
            // use the user‐picked reference_no (no auto‐gen here)
            $referenceNo = $validated['reference_no'];

            DB::transaction(function() use ($validated, $referenceNo) {
                $this->processStockReceipt($validated, $referenceNo);
            });

            session()->forget($sessionKey);

            return redirect()
                ->route('stocks.index')
                ->with('success','Stock received and transaction logged.');

        } catch (\Exception $e) {
            session()->forget($sessionKey);
            Log::error('Failed to create stock', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->with('error','Failed to add stock: '.$e->getMessage())
                ->withInput()
                ->with('show_create_modal', true);
        }
    }


    private function storeMultipleItems(Request $request)
    {
        // pull in all input so we can run a custom Validator
        $data = $request->all();

        $validator = Validator::make($data, [
            'reference_no'          => ['required','regex:/^IAR \d{4}-\d{2}-\d{3}$/'],
            'receipt_date'          => 'required|date|before_or_equal:today',
            'general_remarks'       => 'nullable|string|max:255',
            'general_supplier_id'   => 'nullable|exists:suppliers,id',
            'general_department_id' => 'nullable|exists:departments,id',
            'submission_token'      => 'required|string',
            'items'                 => 'required|array|min:1',
            'items.*.supply_id'     => 'required|exists:supplies,supply_id',
            'items.*.quantity'      => 'required|integer|min:1',
            'items.*.unit_cost'     => 'required|string',
            'items.*.fund_cluster'  => 'required|in:101,151',
            'items.*.status'        => 'required|in:available,reserved,expired,depleted',
            'items.*.expiry_date'   => 'nullable|date',
        ]);

        // after‐hook to enforce per‐supply uniqueness of that IAR
        $validator->after(function($validator) use ($data) {
            $ref = $data['reference_no'];
            foreach ($data['items'] as $i => $item) {
                if (SupplyTransaction::where('reference_no', $ref)
                                    ->where('supply_id', $item['supply_id'])
                                    ->exists())
                {
                    $validator->errors()->add(
                        "items.$i.supply_id",
                        "IAR {$ref} has already been used for that item."
                    );
                }
            }
        });

        // throw if invalid
        $validator->validate();

        try {
            $receiptDate       = Carbon::parse($data['receipt_date']);
            $referenceNo       = $data['reference_no'];
            $generalRemarks    = $data['general_remarks'] ?? '';
            $generalSupplierId = $data['general_supplier_id'] ?? null;
            $generalDeptId     = $data['general_department_id'] ?? null;

            DB::transaction(function() use (
                $data, $referenceNo, $receiptDate,
                $generalRemarks, $generalSupplierId, $generalDeptId
            ) {
                foreach ($data['items'] as $item) {
                    // strip comma from unit_cost
                    $unitCost = str_replace(',','',$item['unit_cost']);
                    $itemData = [
                        'receipt_date'     => $receiptDate->toDateString(),
                        'supply_id'        => $item['supply_id'],
                        'supplier_id'      => $generalSupplierId,
                        'department_id'    => $generalDeptId,
                        'quantity_on_hand' => $item['quantity'],
                        'unit_cost'        => $unitCost,
                        'expiry_date'      => $item['expiry_date'] ?? null,
                        'status'           => $item['status'],
                        'fund_cluster'     => $item['fund_cluster'],
                        'days_to_consume'  => null,
                        'remarks'          => $generalRemarks,
                    ];

                    $this->processStockReceipt($itemData, $referenceNo);
                }
            });

            session()->forget('last_stock_submission_'.auth()->id());

            $count = count($data['items']);
            $text  = $count > 1 ? "{$count} items" : "1 item";

            return redirect()
                ->route('stocks.index')
                ->with('success',"IAR {$referenceNo} created with {$text}.");

        } catch (\Exception $e) {
            session()->forget('last_stock_submission_'.auth()->id());
            Log::error('Failed to create multiple‐item IAR', ['error'=>$e->getMessage()]);

            return back()
                ->with('error','Failed to add stock: '.$e->getMessage())
                ->withInput()
                ->with('show_create_modal', true);
        }
    }


    /**
     * Process a single stock receipt item
     * Extracted to avoid code duplication
     */
    private function processStockReceipt(array $itemData, string $referenceNo)
    {
        // 1) Pull or init summary
        $stock = SupplyStock::firstOrNew([
            'supply_id'    => $itemData['supply_id'],
            'fund_cluster' => $itemData['fund_cluster'],
        ]);

        // 2) existing totals
        $oldQty       = $stock->exists ? $stock->quantity_on_hand : 0;
        $oldTotalCost = $stock->exists ? $stock->total_cost        : 0;

        // 3) this lot
        $newLotQty  = $itemData['quantity_on_hand'];
        $newLotCost = $newLotQty * $itemData['unit_cost'];

        // 4) updated totals
        $newTotalQty  = $oldQty + $newLotQty;
        $newTotalCost = $oldTotalCost + $newLotCost;

        // 5) new weighted-avg cost
        $newAvgCost = $newTotalQty > 0
            ? ($newTotalCost / $newTotalQty)
            : 0;

        // 6) save summary row
        $stock->fill([
            'quantity_on_hand' => $newTotalQty,
            'unit_cost'        => round($newAvgCost, 2),
            'total_cost'       => $newTotalCost,
            'expiry_date'      => $itemData['expiry_date'],
            'status'           => $itemData['status'],
            'days_to_consume'  => $itemData['days_to_consume'] ?? null,
            'remarks'          => $itemData['remarks'],
            'supplier_id'      => $itemData['supplier_id'] ?? null,
            'department_id'    => $itemData['department_id'] ?? null,
        ])->save();

        // 7) log the transaction, but only use the form’s department_id
        $deptId = $itemData['department_id'] ?? null;

        SupplyTransaction::create([
            'supply_id'          => $itemData['supply_id'],
            'transaction_type'   => 'receipt',
            'transaction_date'   => $itemData['receipt_date'],
            'reference_no'       => $referenceNo,
            'quantity'           => $newLotQty,
            'unit_cost'          => $itemData['unit_cost'],
            'total_cost'         => $newLotCost,
            'balance_quantity'   => $newTotalQty,
            'balance_unit_cost'  => round($newAvgCost, 2),
            'balance_total_cost' => $newTotalCost,
            'department_id'      => $deptId,      // 👈 only if provided (can be null)
            'user_id'            => auth()->id(),
            'remarks'            => $itemData['remarks']
                                    ?? 'Stock receipt via IAR '.$referenceNo,
            'fund_cluster'       => $itemData['fund_cluster'],
            'days_to_consume'    => $itemData['days_to_consume'] ?? null,
        ]);

        // 8) debug
        Log::info('Stock Receipt Added', [
            'iar_reference' => $referenceNo,
            'supply_id'     => $itemData['supply_id'],
            'receipt_date'  => $itemData['receipt_date'],
            'new_total_qty' => $newTotalQty,
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

        // UPDATED VALIDATION - Added receipt_date and reference_no
        $validated = $request->validate([
            'unit_cost'        => 'required|numeric|min:0',
            'supplier_id'      => 'nullable|exists:suppliers,id',
            'department_id'    => 'nullable|exists:departments,id',
            'expiry_date'      => 'nullable|date',
            'status'           => 'required|in:available,reserved,expired,depleted',
            'fund_cluster'     => 'required|in:101,151',
            'days_to_consume'  => 'nullable|integer|min:0',
            'remarks'          => 'nullable|string',
            'receipt_date'     => 'nullable|date|before_or_equal:today',
            'reference_no'     => 'nullable|string|max:255',
            'submission_token' => 'required|string',
        ]);

        try {
            $stock = SupplyStock::findOrFail($id);

            // We keep the same quantity but re‑value at the new unit_cost
            $currentQty = $stock->quantity_on_hand;
            $validated['total_cost'] = $currentQty * $validated['unit_cost'];

            // Update stock with all validated fields, including remarks
            $stock->update($validated);

            // ============================================================================
            // FIX: Use the same pattern as SupplyTransactionController
            // Only set department_id if it's actually provided and not empty
            // ============================================================================

            // Prepare transaction data
            $transactionData = [
                'supply_id'        => $stock->supply_id,
                'transaction_type' => 'adjustment',
                'transaction_date' => $validated['receipt_date'] ?? now()->toDateString(),
                'reference_no'     => $validated['reference_no'] ?? 'Re-valuation',
                'quantity'         => 0, // Zero quantity for re-valuation
                'unit_cost'        => $validated['unit_cost'],
                'total_cost'       => 0, // Zero cost impact
                'balance_quantity' => $stock->quantity_on_hand,
                'balance_unit_cost' => $validated['unit_cost'],
                'balance_total_cost' => $validated['total_cost'],
                'user_id'          => auth()->id(),
                'remarks'          => $validated['remarks'] ?? 'Stock re-valued',
                'fund_cluster'     => $validated['fund_cluster'],
                'days_to_consume'  => $validated['days_to_consume'],
            ];

            // Only set department_id if it's actually provided and not empty
            if (!empty($validated['department_id'])) {
                $transactionData['department_id'] = $validated['department_id'];
            }
            // If not provided, department_id will remain null (not set)

            // Create an adjustment transaction to log this change
            SupplyTransaction::create($transactionData);

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


    public function getIarData($stockId)
    {
        try {
            $stock = SupplyStock::with(['supply'])->findOrFail($stockId);

            // Get the latest receipt transaction for this stock
            $latestReceipt = SupplyTransaction::where('supply_id', $stock->supply_id)
                ->where('fund_cluster', $stock->fund_cluster)
                ->where('transaction_type', 'receipt')
                ->where('reference_no', 'like', 'IAR %')
                ->orderBy('transaction_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->first();

            $iarData = [
                'stock_id' => $stock->stock_id,
                'supply_id' => $stock->supply_id,
                'supply_name' => $stock->supply->item_name ?? 'N/A',
                'current_quantity' => number_format($stock->quantity_on_hand) . ' ' . ($stock->supply->unit_of_measurement ?? ''),
                'unit_cost' => number_format($stock->unit_cost, 2, '.', ''),
                'status' => $stock->status,
                'expiry_date' => $stock->expiry_date ? $stock->expiry_date->format('Y-m-d') : '',
                'fund_cluster' => $stock->fund_cluster,
                'days_to_consume' => $stock->days_to_consume,
                'remarks' => $stock->remarks,
                'supplier_id' => $stock->supplier_id,
                'department_id' => $stock->department_id,
                'receipt_date' => $latestReceipt ? \Carbon\Carbon::parse($latestReceipt->transaction_date)->format('Y-m-d') : now()->format('Y-m-d'),
                'reference_no' => $latestReceipt ? $latestReceipt->reference_no : '',
            ];

            return response()->json([
                'success' => true,
                'data' => $iarData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch stock data'
            ], 404);
        }
    }


}
