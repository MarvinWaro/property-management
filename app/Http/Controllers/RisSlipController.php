<?php

namespace App\Http\Controllers;

use App\Models\RisSlip;
use App\Models\RisItem;
use App\Models\Supply;
use App\Models\Department;
use App\Models\SupplyStock;
use App\Models\SupplyTransaction; // Add this import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\ReferenceNumberService;

class RisSlipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = RisSlip::with(['department', 'requester'])
                    ->orderBy('created_at', 'desc');

        // Add search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ris_no', 'like', "%{$search}%")
                ->orWhereHas('requester', function($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%");
                })
                    ->orWhereHas('department', function($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        $risSlips = $query->paginate(10)->withQueryString();
        return view('ris.index', compact('risSlips'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ReferenceNumberService $referenceNumberService)
    {
        // Validate the form data
        $validated = $request->validate([
            'entity_name' => 'required|string',
            'division' => 'required|exists:departments,id',
            'office' => 'nullable|string',
            'fund_cluster' => 'nullable|string',
            'responsibility_center_code' => 'nullable|string',
            'purpose' => 'required|string',
            'supplies' => 'required|array',
            'supplies.*.supply_id' => 'required|exists:supplies,supply_id',
            'supplies.*.quantity' => 'required|integer|min:1',
            'signature_type' => 'required|in:esign,sgd', // Add this validation
        ]);

        // Generate RIS number using the service
        $newRisNumber = $referenceNumberService->generateRisNumber();

        // Begin transaction
        return DB::transaction(function() use ($validated, $newRisNumber) {
            // Create RIS Slip
            $risSlip = RisSlip::create([
                'ris_no' => $newRisNumber,
                'ris_date' => now(),
                'entity_name' => $validated['entity_name'],
                'division' => $validated['division'],
                'office' => $validated['office'],
                'fund_cluster' => $validated['fund_cluster'],
                'responsibility_center_code' => $validated['responsibility_center_code'],
                'requested_by' => Auth::id(),
                'requester_signature_type' => $validated['signature_type'], // Add this field
                'purpose' => $validated['purpose'],
                'status' => 'draft',
            ]);

            // Create RIS Items
            foreach ($validated['supplies'] as $item) {
                // Check availability across all fund clusters
                $stockAvailable = SupplyStock::where('supply_id', $item['supply_id'])
                    ->where('status', 'available')
                    ->sum('quantity_on_hand') >= $item['quantity'];

                RisItem::create([
                    'ris_id' => $risSlip->ris_id,
                    'supply_id' => $item['supply_id'],
                    'quantity_requested' => $item['quantity'],
                    'stock_available' => $stockAvailable,
                ]);
            }

            return redirect()->back()
                            ->with('success', 'Requisition created successfully with RIS# ' . $newRisNumber);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(RisSlip $risSlip)
    {
        $risSlip->load(['department', 'requester', 'items.supply']);

        // Calculate availability for each item across all fund clusters
        foreach ($risSlip->items as $item) {
            $item->total_available = SupplyStock::where('supply_id', $item->supply_id)
                ->where('status', 'available')
                ->sum('quantity_on_hand');

            // Get available in the specific fund cluster
            $item->matching_fund_available = SupplyStock::where('supply_id', $item->supply_id)
                ->where('status', 'available')
                ->where('fund_cluster', $risSlip->fund_cluster)
                ->sum('quantity_on_hand');
        }

        return view('ris.show', compact('risSlip'));
    }

    /**
     * Approve the RIS.
     */
    public function approve(Request $request, RisSlip $risSlip)
    {
        if ($risSlip->status !== 'draft') {
            return back()->with('error', 'This RIS cannot be approved.');
        }

        // Validate the fund cluster and signature type
        $validated = $request->validate([
            'fund_cluster' => 'nullable|string',
            'signature_type' => 'required|in:esign,sgd', // Add this validation
        ]);

        $risSlip->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'approver_signature_type' => $validated['signature_type'], // Add this field
            'fund_cluster' => $validated['fund_cluster'] ?? $risSlip->fund_cluster,
        ]);

        return back()->with('success', 'RIS approved successfully.');
    }

    public function issue(Request $request, RisSlip $risSlip)
    {
        if ($risSlip->status !== 'approved') {
            return back()->with('error', 'This RIS cannot be issued.');
        }

        // Validate the quantities, remarks, and signature type
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:ris_items,item_id',
            'items.*.quantity_issued' => 'required|integer|min:0',
            'items.*.remarks' => 'nullable|string',
            'received_by' => 'nullable|exists:users,id',
            'signature_type' => 'required|in:esign,sgd', // Add this validation
        ]);

        return DB::transaction(function() use ($validated, $risSlip, $request) {
            // Process each item
            foreach ($validated['items'] as $item) {
                $risItem = RisItem::findOrFail($item['item_id']);

                if ($item['quantity_issued'] > 0) {
                    $risItem->update([
                        'quantity_issued' => $item['quantity_issued'],
                        'remarks' => $item['remarks'] ?? null,
                    ]);

                    // Get all available stocks for this supply
                    $matchingStocks = SupplyStock::where('supply_id', $risItem->supply_id)
                        ->where('status', 'available')
                        ->where('quantity_on_hand', '>', 0)
                        ->orderBy('expiry_date', 'asc') // Use FIFO approach - oldest stock first
                        ->get();

                    // Calculate current balance before issuance
                    $currentBalance = SupplyStock::where('supply_id', $risItem->supply_id)
                        ->where('status', 'available')
                        ->sum('quantity_on_hand');

                    // Process the issuance from stocks
                    $remainingQuantity = $item['quantity_issued'];
                    $totalCost = 0;

                    foreach ($matchingStocks as $stock) {
                        if ($remainingQuantity <= 0) break;

                        $qtyToDeduct = min($remainingQuantity, $stock->quantity_on_hand);

                        // Update stock quantity
                        $stock->quantity_on_hand -= $qtyToDeduct;
                        $stockItemCost = $qtyToDeduct * $stock->unit_cost;
                        $totalCost += $stockItemCost;

                        // Update or mark as depleted if zero
                        if ($stock->quantity_on_hand <= 0) {
                            $stock->status = 'depleted';
                        }

                        $stock->save();

                        // Calculate the new balance after this deduction
                        $newBalance = $currentBalance - $qtyToDeduct;

                        // Create the transaction using direct DB query to ensure all fields are included
                        $transaction = DB::table('supply_transactions')->insertGetId([
                            'supply_id' => $risItem->supply_id,
                            'transaction_type' => 'issue',
                            'transaction_date' => now()->toDateString(),
                            'reference_no' => $risSlip->ris_no,
                            'quantity' => $qtyToDeduct,
                            'unit_cost' => $stock->unit_cost,
                            'total_cost' => $stockItemCost,
                            'department_id' => $risSlip->division,
                            'remarks' => "Issued via RIS #{$risSlip->ris_no}",
                            'balance_quantity' => $newBalance,
                            'user_id' => Auth::id(), // Ensure this field is set
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        // Set up the many-to-many relationships if needed
                        if ($risSlip->requested_by) {
                            DB::table('user_transactions')->insert([
                                'transaction_id' => $transaction,
                                'user_id' => $risSlip->requested_by,
                                'role' => 'requester',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }

                        if ($request->received_by) {
                            DB::table('user_transactions')->insert([
                                'transaction_id' => $transaction,
                                'user_id' => $request->received_by,
                                'role' => 'receiver',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }

                        $remainingQuantity -= $qtyToDeduct;
                        $currentBalance = $newBalance; // Update current balance for next iteration
                    }
                }
            }

            // Update the RIS status
            $risSlip->update([
                'status' => 'posted',
                'issued_by' => Auth::id(),
                'issued_at' => now(),
                'issuer_signature_type' => $validated['signature_type'], // Add this field
                'received_by' => $request->received_by ?? null,
            ]);

            return redirect()->route('ris.show', $risSlip)
                            ->with('success', 'Supplies issued successfully.');
        });
    }

    public function receive(Request $request, RisSlip $risSlip)
    {
        // Validate the signature type
        $validated = $request->validate([
            'signature_type' => 'required|in:esign,sgd', // Add this validation
        ]);

        // Check if the current user is the one assigned to receive
        if ($risSlip->received_by !== auth()->id()) {
            return back()->with('error', 'You are not authorized to receive this RIS.');
        }

        // Check if already received
        if ($risSlip->received_at) {
            return back()->with('error', 'This RIS has already been received.');
        }

        // Check if the RIS has been issued
        if ($risSlip->status !== 'posted' || !$risSlip->issued_at) {
            return back()->with('error', 'This RIS has not been issued yet.');
        }

        // Update the received timestamp with signature type
        $risSlip->update([
            'received_at' => now(),
            'receiver_signature_type' => $validated['signature_type'], // Add this field
        ]);

        return back()->with('success', 'Supplies received successfully.');
    }


    /**
     * Print the RIS form.
     */
    public function print(RisSlip $risSlip)
    {
        $risSlip->load(['department', 'requester', 'approver', 'issuer', 'receiver', 'items.supply']);
        return view('ris.print', compact('risSlip'));
    }
}
