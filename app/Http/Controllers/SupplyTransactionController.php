<?php

namespace App\Http\Controllers;

use App\Models\SupplyTransaction;
use App\Models\SupplyStock;
use App\Models\Supply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ReferenceNumberService;

class SupplyTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = SupplyTransaction::with(['supply', 'department', 'user'])
                    ->orderBy('created_at', 'desc'); // Changed from transaction_date to created_at

        if ($request->filled('from')) $query->whereDate('transaction_date', '>=', $request->from);
        if ($request->filled('to'))   $query->whereDate('transaction_date', '<=', $request->to);
        if ($request->filled('type')) $query->where('transaction_type', $request->type);

        $txns = $query->paginate(25)->withQueryString();
        return view('supply-transaction.index', compact('txns'));
    }

    public function show(SupplyTransaction $supplyTransaction)
    {
        return view('supply-transaction.show', ['txn' => $supplyTransaction]);
    }

    public function store(Request $request, ReferenceNumberService $referenceNumberService)
    {
        /* 1 ▸ Validate input */
        $data = $request->validate([
            'supply_id'        => 'required|exists:supplies,supply_id',
            'transaction_type' => 'required|in:receipt,issue,adjustment',
            'transaction_date' => 'required|date',
            'reference_no'     => 'nullable|string', // Changed to nullable
            'quantity'         => 'required|integer|min:0',   // qty 0 allowed for adjustment
            'unit_cost'        => 'required|numeric|min:0',
            'department_id'    => 'nullable|exists:departments,id', // CHANGED TO NULLABLE
            'remarks'          => 'nullable|string',
            'requested_by'     => 'nullable|exists:users,id',  // Added for requester
            'received_by'      => 'nullable|exists:users,id',  // Added for receiver
            'fund_cluster'     => 'nullable|string', // Added for fund cluster
            'days_to_consume'  => 'nullable|integer', // Added for days to consume
            'ris_id'           => 'nullable|exists:ris_slips,ris_id', // Add this to link with RIS slip
        ]);

        /* Generate reference number if not provided */
        if (empty($data['reference_no'])) {
            if ($data['transaction_type'] === 'receipt') {
                // Generate IAR number for receipt transactions
                $data['reference_no'] = $referenceNumberService->generateIarNumber($data['supply_id']);
            } elseif ($data['transaction_type'] === 'issue') {
                // Use RIS number from the RIS slip if available, or generate a new one
                if (!empty($data['ris_id'])) {
                    $risSlip = \App\Models\RisSlip::find($data['ris_id']);
                    $data['reference_no'] = $risSlip->ris_no;
                } else {
                    $data['reference_no'] = $referenceNumberService->generateRisNumber();
                }
            }
        }

        /* ▲ NEW: record who performed the transaction */
        $data['user_id'] = auth()->id();

        // IMPORTANT: Only set department_id if it's actually provided and not empty
        if (empty($data['department_id'])) {
            unset($data['department_id']);
        }

        /* 2 ▸ Lock summary row */
        $stock = SupplyStock::where('supply_id', $data['supply_id'])
                 ->lockForUpdate()
                 ->firstOrFail();

        /* 3 ▸ Derived values */
        $data['total_cost'] = $data['unit_cost'] * $data['quantity'];

        $deltaQty = match ($data['transaction_type']) {
            SupplyTransaction::RECEIPT    =>  $data['quantity'],
            SupplyTransaction::ISSUE      => -$data['quantity'],
            SupplyTransaction::ADJUSTMENT =>  0,
        };

        $newQty = $stock->quantity_on_hand + $deltaQty;
        if ($newQty < 0) {
            return back()->withErrors('Issuance would make quantity negative.');
        }

        $newUnitCost = match ($data['transaction_type']) {
            SupplyTransaction::RECEIPT =>
                $newQty ? ($stock->total_cost + $data['total_cost']) / $newQty
                        : $stock->unit_cost,
            SupplyTransaction::ISSUE      => $stock->unit_cost,
            SupplyTransaction::ADJUSTMENT => $data['unit_cost'],
        };

        /* 4 ▸ Atomic save */
        DB::transaction(function () use ($data, $stock, $newQty, $newUnitCost) {
            $data['balance_quantity'] = $newQty;

            // Create the transaction
            $transaction = SupplyTransaction::create($data);   // now includes user_id

            // Link transaction to requester if provided
            if (!empty($data['requested_by'])) {
                $transaction->users()->attach($data['requested_by'], ['role' => 'requester']);
            }

            // Link transaction to receiver if provided
            if (!empty($data['received_by'])) {
                $transaction->users()->attach($data['received_by'], ['role' => 'receiver']);
            }

            $stock->update([
                'quantity_on_hand' => $newQty,
                'unit_cost'        => $newUnitCost,
                'total_cost'       => $newQty * $newUnitCost,
            ]);
        });

        return back()->with('success', 'Transaction recorded successfully.');
    }
}
