<?php

namespace App\Http\Controllers;

use App\Models\RisSlip;
use App\Models\RisItem;
use App\Models\Supply;
use App\Models\Department;
use App\Models\SupplyStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RisSlipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $risSlips = RisSlip::with(['division', 'requester'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('ris.index', compact('risSlips'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
        ]);

        // Generate RIS number
        $latestRis = RisSlip::latest('ris_id')->first();
        $newRisNumber = 'RIS-' . date('Ym') . '-' . str_pad(($latestRis ? ($latestRis->ris_id + 1) : 1), 4, '0', STR_PAD_LEFT);

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
                'purpose' => $validated['purpose'],
                'status' => 'draft',
            ]);

            // Create RIS Items
            foreach ($validated['supplies'] as $item) {
                // Check stock availability
                $stockAvailable = SupplyStock::where('supply_id', $item['supply_id'])
                    ->where('status', 'available')
                    ->where('quantity_on_hand', '>=', $item['quantity'])
                    ->exists();

                RisItem::create([
                    'ris_id' => $risSlip->ris_id,
                    'supply_id' => $item['supply_id'],
                    'quantity_requested' => $item['quantity'],
                    'stock_available' => $stockAvailable,
                ]);
            }

            return redirect()->back()->with('success', 'Requisition created successfully with RIS# ' . $newRisNumber);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(RisSlip $risSlip)
    {
        $risSlip->load(['division', 'requester', 'items.supply']);
        return view('ris.show', compact('risSlip'));
    }

    /**
     * Approve the RIS.
     */
    public function approve(RisSlip $risSlip)
    {
        if ($risSlip->status !== 'draft') {
            return back()->with('error', 'This RIS cannot be approved.');
        }

        $risSlip->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'RIS approved successfully.');
    }

    /**
     * Process the issuance.
     */
    public function issue(Request $request, RisSlip $risSlip)
    {
        if ($risSlip->status !== 'approved') {
            return back()->with('error', 'This RIS cannot be issued.');
        }

        // Validate the quantities
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:ris_items,item_id',
            'items.*.quantity_issued' => 'required|integer|min:0',
        ]);

        return DB::transaction(function() use ($validated, $risSlip, $request) {
            // Process each item
            foreach ($validated['items'] as $item) {
                $risItem = RisItem::findOrFail($item['item_id']);

                // Only process items that have quantities to issue
                if ($item['quantity_issued'] > 0) {
                    // Update the item with issued quantity
                    $risItem->update([
                        'quantity_issued' => $item['quantity_issued'],
                        'remarks' => $item['remarks'] ?? null,
                    ]);

                    // Find the stock
                    $stock = SupplyStock::where('supply_id', $risItem->supply_id)
                                ->where('status', 'available')
                                ->first();

                    if ($stock) {
                        // Create issue transaction
                        app(SupplyTransactionController::class)->store(new Request([
                            'supply_id' => $risItem->supply_id,
                            'transaction_type' => 'issue',
                            'transaction_date' => now()->toDateString(),
                            'reference_no' => $risSlip->ris_no,
                            'quantity' => $item['quantity_issued'],
                            'unit_cost' => $stock->unit_cost,
                            'department_id' => $risSlip->division,
                            'remarks' => "Issued via RIS #{$risSlip->ris_no}",
                        ]));
                    }
                }
            }

            // Update the RIS status
            $risSlip->update([
                'status' => 'posted',
                'issued_by' => Auth::id(),
                'issued_at' => now(),
                'received_by' => $request->received_by ?? null,
                'received_at' => $request->received_by ? now() : null,
            ]);

            return redirect()->route('ris.show', $risSlip)->with('success', 'Supplies issued successfully.');
        });
    }
    /**
     * Print the RIS form
     */
    public function print(RisSlip $risSlip)
    {
        $risSlip->load(['division', 'requester', 'approver', 'issuer', 'receiver', 'items.supply']);
        return view('ris.print', compact('risSlip'));
    }
}
