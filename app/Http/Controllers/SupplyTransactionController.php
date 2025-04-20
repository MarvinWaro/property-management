<?php
// Controller: app/Http/Controllers/SupplyTransactionController.php
namespace App\Http\Controllers;

use App\Models\SupplyTransaction;
use App\Models\SupplyStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplyTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = SupplyTransaction::with(['supply', 'department', 'user'])
                    ->orderBy('transaction_date', 'desc');

        if ($request->filled('from')) {
            $query->whereDate('transaction_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('transaction_date', '<=', $request->to);
        }
        if ($request->filled('type')) {
            $query->where('transaction_type', $request->type);
        }

        $txns = $query->paginate(25)->withQueryString();
        return view('supply-transaction.index', compact('txns'));
    }

    public function show(SupplyTransaction $supplyTransaction)
    {
        return view('supply-transaction.show', ['txn' => $supplyTransaction]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supply_id'        => 'required|exists:supplies,supply_id',
            'transaction_type' => 'required|in:receipt,issue,adjustment',
            'transaction_date' => 'required|date',
            'reference_no'     => 'required|string',
            'quantity'         => 'required|integer|min:1',
            'unit_cost'        => 'required|numeric|min:0',
            'department_id'    => 'required|exists:departments,id',
            'remarks'          => 'nullable|string',
        ]);

        $stock = SupplyStock::where('supply_id', $data['supply_id'])->firstOrFail();
        $data['total_cost'] = $data['unit_cost'] * $data['quantity'];
        $data['balance_quantity'] = $stock->quantity_on_hand + ($data['transaction_type'] === 'receipt' ? $data['quantity'] : -$data['quantity']);

        DB::transaction(function() use ($data, $stock) {
            SupplyTransaction::create($data);
            $stock->update([
                'quantity_on_hand' => $data['balance_quantity'],
                'unit_cost'        => $data['unit_cost'],
            ]);
        });

        return back()->with('success', 'Transaction recorded successfully.');
    }
}
