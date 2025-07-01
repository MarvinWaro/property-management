<?php
namespace App\Http\Controllers;

use App\Models\RisSlip;
use App\Models\RisItem;
use App\Models\Supply;
use App\Models\Department;
use App\Models\SupplyStock;
use App\Models\SupplyTransaction;
use App\Models\User; // <-- ADD THIS IMPORT
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // <-- ADD THIS IMPORT
use App\Services\ReferenceNumberService;
use App\Events\RequisitionStatusUpdated;
use App\Events\UserNotificationUpdated;
use App\Constants\RisStatus;
use Carbon\Carbon; // <-- ADD THIS IMPORT

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class RisSlipController extends Controller
{

    /**
     * Display a listing of the resource with manual entry support.
     */
    public function index(Request $request)
    {
        // Get total counts for stats using model scopes
        $totalCount = RisSlip::count();
        $pendingCount = RisSlip::draft()->count();
        $approvedCount = RisSlip::approved()->count();
        $pendingReceiptCount = RisSlip::pendingReceipt()->count();
        $completedCount = RisSlip::completed()->count();
        $declinedCount = RisSlip::declined()->count();

        // Start building the query with relationships
        $query = RisSlip::with(['department', 'requester', 'approver', 'issuer', 'receiver', 'decliner'])
                    ->orderBy('created_at', 'desc');

        // Apply status filter using constants
        if ($request->filled('status')) {
            switch ($request->status) {
                case RisStatus::DRAFT:
                    $query->draft();
                    break;
                case RisStatus::APPROVED:
                    $query->approved();
                    break;
                case 'pending-receipt':
                    $query->pendingReceipt();
                    break;
                case 'completed':
                    $query->completed();
                    break;
                case RisStatus::DECLINED:
                    $query->declined();
                    break;
                case RisStatus::POSTED:
                    $query->posted();
                    break;
            }
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ris_no', 'like', "%{$search}%")
                ->orWhere('purpose', 'like', "%{$search}%")
                ->orWhere('entity_name', 'like', "%{$search}%")
                ->orWhere('office', 'like', "%{$search}%")
                ->orWhereHas('requester', function($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('department', function($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Apply date range filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Apply department filter if provided
        if ($request->filled('department')) {
            $query->where('division', $request->department);
        }

        // Paginate with query string preservation
        $risSlips = $query->paginate(10)->appends($request->query());

        // Get departments for filter dropdown
        $departments = Department::orderBy('name')->get();

        // NEW: Get data for manual entry modal (only for admin/cao)
        $users = collect();
        $availableSupplies = collect();

        if (auth()->user()->hasRole(['admin', 'cao'])) {
            // FIXED: Get all active users for manual entry dropdown
            $users = User::where('status', true)  // <-- CHANGED from 'is_active' to 'status'
                ->with('department')  // <-- ADDED with('department') for proper loading
                ->orderBy('name')
                ->get(['id', 'name', 'email', 'department_id'])
                ->map(function ($user) {
                    // Add department name for better display
                    $user->department_name = $user->department ? $user->department->name : 'No Department';
                    return $user;
                });

            // Check if we should show all supplies or only those with stock
            $showAllSupplies = $request->get('show_all_supplies', false);

            if ($showAllSupplies) {
                // Show ALL active supplies for historical entry
                $availableSupplies = Supply::where('is_active', true)  // <-- This is correct (supplies table has is_active)
                    ->orderBy('item_name')
                    ->get()
                    ->map(function ($supply) {
                        // Get stock info if available
                        $stock = SupplyStock::where('supply_id', $supply->supply_id)
                            ->where('status', 'available')
                            ->where('quantity_on_hand', '>', 0)
                            ->first();

                        $quantity_on_hand = $stock ? $stock->quantity_on_hand : 0;

                        // Calculate pending requests
                        $pendingRequested = RisItem::where('supply_id', $supply->supply_id)
                            ->whereHas('risSlip', function($q) {
                                $q->whereIn('status', ['draft', 'approved']);
                            })
                            ->sum('quantity_requested');

                        $actualAvailable = max(0, $quantity_on_hand - $pendingRequested);

                        // Create a structured object for frontend
                        return (object) [
                            'supply_id' => $supply->supply_id,
                            'supply' => $supply,
                            'quantity_on_hand' => $quantity_on_hand,
                            'actual_available' => $actualAvailable,
                            'pending_requested' => $pendingRequested,
                            'display_name' => $supply->item_name . ' (' . $supply->stock_no . ')',
                            'full_description' => $supply->description,
                            'unit' => $supply->unit_of_measurement,
                            'has_stock' => $stock !== null,
                            'fund_cluster' => $stock ? $stock->fund_cluster : null,
                            'stock_no' => $supply->stock_no,
                            'item_name' => $supply->item_name,
                            'description' => $supply->description,
                            'unit_of_measurement' => $supply->unit_of_measurement
                        ];
                    });

                \Log::info('Showing all supplies for manual entry', [
                    'total_supplies' => $availableSupplies->count(),
                    'with_stock' => $availableSupplies->where('has_stock', true)->count(),
                    'without_stock' => $availableSupplies->where('has_stock', false)->count()
                ]);

            } else {
                // Original logic - only supplies with available stock
                $availableSupplies = SupplyStock::with(['supply'])
                    ->where('status', 'available')
                    ->where('quantity_on_hand', '>', 0)
                    ->get()
                    ->map(function ($stock) {
                        // Calculate real available quantity (minus pending requests)
                        $pendingRequested = RisItem::where('supply_id', $stock->supply_id)
                            ->whereHas('risSlip', function($q) {
                                $q->whereIn('status', ['draft', 'approved']);
                            })
                            ->sum('quantity_requested');

                        $actualAvailable = max(0, $stock->quantity_on_hand - $pendingRequested);

                        // Only include if there's actually available quantity
                        if ($actualAvailable > 0) {
                            // Add calculated availability to the stock object
                            $stock->actual_available = $actualAvailable;
                            $stock->pending_requested = $pendingRequested;

                            // Add supply details for easier access in frontend
                            $stock->display_name = $stock->supply->item_name . ' (' . $stock->supply->stock_no . ')';
                            $stock->full_description = $stock->supply->description;
                            $stock->unit = $stock->supply->unit_of_measurement;
                            $stock->has_stock = true;

                            // Add these for consistency with the show all supplies structure
                            $stock->stock_no = $stock->supply->stock_no;
                            $stock->item_name = $stock->supply->item_name;
                            $stock->description = $stock->supply->description;
                            $stock->unit_of_measurement = $stock->supply->unit_of_measurement;

                            return $stock;
                        }

                        return null;
                    })
                    ->filter() // Remove null entries
                    ->values() // Reset array keys
                    ->sortBy('display_name'); // Sort by display name for better UX
            }

            // Debug log
            \Log::info('Manual RIS Entry Supplies', [
                'total_available_supplies' => $availableSupplies->count(),
                'show_all_supplies' => $showAllSupplies,
                'user' => auth()->user()->name,
                'first_few' => $availableSupplies->take(3)->map(function($s) {
                    return [
                        'name' => $s->display_name ?? 'N/A',
                        'available' => $s->actual_available ?? 0,
                        'has_stock' => $s->has_stock ?? false
                    ];
                })
            ]);
        }

        return view('ris.index', compact(
            'risSlips',
            'totalCount',
            'pendingCount',
            'approvedCount',
            'pendingReceiptCount',
            'completedCount',
            'declinedCount',
            'departments',
            'users',
            'availableSupplies'
        ));
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
            'signature_type' => 'required|in:esign,sgd',
        ]);

        // NEW: Check stock availability before processing
        $stockValidationErrors = [];
        $currentAvailability = [];

        foreach ($validated['supplies'] as $index => $item) {
            $supplyId = $item['supply_id'];
            $requestedQty = $item['quantity'];

            // Calculate current real-time availability
            $stock = SupplyStock::where('supply_id', $supplyId)
                ->where('status', 'available')
                ->where('quantity_on_hand', '>', 0)
                ->first();

            if (!$stock) {
                $stockValidationErrors[] = [
                    'supply_id' => $supplyId,
                    'message' => 'Item is no longer available',
                    'available' => 0
                ];
                continue;
            }

            $pendingRequested = RisItem::where('supply_id', $supplyId)
                ->whereHas('risSlip', function($q) {
                    $q->whereIn('status', ['draft','approved']);
                })
                ->sum('quantity_requested');

            $currentAvailable = max(0, $stock->quantity_on_hand - $pendingRequested);
            $currentAvailability[$supplyId] = $currentAvailable;

            if ($requestedQty > $currentAvailable) {
                $supply = Supply::find($supplyId);
                $stockValidationErrors[] = [
                    'supply_id' => $supplyId,
                    'supply_name' => $supply ? $supply->item_name : 'Unknown Item',
                    'requested' => $requestedQty,
                    'available' => $currentAvailable,
                    'message' => "Only {$currentAvailable} units available (requested: {$requestedQty})"
                ];
            }
        }

        // If there are stock validation errors, return them
        if (!empty($stockValidationErrors)) {
            return response()->json([
                'success' => false,
                'type' => 'stock_validation_error',
                'message' => 'Stock availability has changed. Please review and update your request.',
                'errors' => $stockValidationErrors,
                'current_availability' => $currentAvailability
            ], 422);
        }

        $maxAttempts = 5;
        $attempt = 0;

        do {
            $attempt++;
            // Generate a new RIS number each attempt
            $newRisNumber = $referenceNumberService->generateRisNumber();

            try {
                // Begin transaction
                return DB::transaction(function() use ($validated, $newRisNumber) {
                    // Check again to avoid any duplicate number
                    if (\App\Models\RisSlip::where('ris_no', $newRisNumber)->exists()) {
                        throw new \Exception("RIS number already exists, retrying...");
                    }

                    // Create RIS Slip
                    $risSlip = \App\Models\RisSlip::create([
                        'ris_no' => $newRisNumber,
                        'ris_date' => now(),
                        'entity_name' => $validated['entity_name'],
                        'division' => $validated['division'],
                        'office' => $validated['office'],
                        'fund_cluster' => $validated['fund_cluster'],
                        'responsibility_center_code' => $validated['responsibility_center_code'],
                        'requested_by' => Auth::id(),
                        'requester_signature_type' => $validated['signature_type'],
                        'purpose' => $validated['purpose'],
                        'status' => 'draft',
                    ]);

                    // Create RIS Items
                    foreach ($validated['supplies'] as $item) {
                        $stockAvailable = \App\Models\SupplyStock::where('supply_id', $item['supply_id'])
                            ->where('status', 'available')
                            ->sum('quantity_on_hand') >= $item['quantity'];

                        \App\Models\RisItem::create([
                            'ris_id' => $risSlip->ris_id,
                            'supply_id' => $item['supply_id'],
                            'quantity_requested' => $item['quantity'],
                            'stock_available' => $stockAvailable,
                        ]);
                    }

                    // Broadcast the event for new requisition
                    broadcast(new RequisitionStatusUpdated($risSlip, 'created'));

                    return response()->json([
                        'success' => true,
                        'message' => 'Requisition created successfully with RIS# ' . $newRisNumber,
                        'ris_number' => $newRisNumber
                    ]);
                });
            } catch (\Exception $e) {
                // Retry only for RIS number conflict
                if ($attempt >= $maxAttempts || strpos($e->getMessage(), 'retrying') === false) {
                    throw $e;
                }
                usleep(150000); // 0.15s sleep before retry
            }
        } while ($attempt < $maxAttempts);

        return response()->json([
            'success' => false,
            'message' => 'Failed to generate unique RIS number after several attempts.'
        ], 500);
    }

    public function validateStock(Request $request)
    {
        $validated = $request->validate([
            'supplies' => 'required|array',
            'supplies.*.supply_id' => 'required|exists:supplies,supply_id',
            'supplies.*.quantity' => 'required|integer|min:1',
        ]);

        $stockValidationErrors = [];
        $currentAvailability = [];

        foreach ($validated['supplies'] as $index => $item) {
            $supplyId = $item['supply_id'];
            $requestedQty = $item['quantity'];

            // Calculate current real-time availability
            $stock = SupplyStock::where('supply_id', $supplyId)
                ->where('status', 'available')
                ->where('quantity_on_hand', '>', 0)
                ->first();

            if (!$stock) {
                $stockValidationErrors[] = [
                    'supply_id' => $supplyId,
                    'message' => 'Item is no longer available',
                    'available' => 0
                ];
                $currentAvailability[$supplyId] = 0;
                continue;
            }

            $pendingRequested = RisItem::where('supply_id', $supplyId)
                ->whereHas('risSlip', function($q) {
                    $q->whereIn('status', ['draft','approved']);
                })
                ->sum('quantity_requested');

            $currentAvailable = max(0, $stock->quantity_on_hand - $pendingRequested);
            $currentAvailability[$supplyId] = $currentAvailable;

            if ($requestedQty > $currentAvailable) {
                $supply = Supply::find($supplyId);
                $stockValidationErrors[] = [
                    'supply_id' => $supplyId,
                    'supply_name' => $supply ? $supply->item_name : 'Unknown Item',
                    'requested' => $requestedQty,
                    'available' => $currentAvailable,
                    'message' => "Only {$currentAvailable} units available (requested: {$requestedQty})"
                ];
            }
        }

        return response()->json([
            'success' => empty($stockValidationErrors),
            'errors' => $stockValidationErrors,
            'current_availability' => $currentAvailability
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(RisSlip $risSlip)
    {
        $risSlip->load(['department', 'requester', 'items.supply', 'decliner']);

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
            'signature_type' => 'required|in:esign,sgd',
        ]);

        $risSlip->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'approver_signature_type' => $validated['signature_type'],
            'fund_cluster' => $validated['fund_cluster'] ?? $risSlip->fund_cluster,
        ]);

        // Broadcast events
        broadcast(new RequisitionStatusUpdated($risSlip, 'approved'));

        if ($risSlip->requested_by) {
            broadcast(new UserNotificationUpdated(
                $risSlip->requested_by,
                'requisition_approved',
                ['ris_no' => $risSlip->ris_no, 'ris_id' => $risSlip->ris_id]
            ));
        }

        return back()->with('success', 'RIS approved successfully.');
    }

    /**
     * Decline the RIS.
     */
    public function decline(Request $request, RisSlip $risSlip)
    {
        // Validate that the RIS can be declined
        if (!$risSlip->canBeDeclined()) {
            return back()->with('error', 'This RIS cannot be declined. Only draft requisitions can be declined.');
        }

        // Validate the decline reason
        $validated = $request->validate([
            'decline_reason' => 'required|string|min:10|max:500',
        ], [
            'decline_reason.required' => 'A decline reason is required.',
            'decline_reason.min' => 'The decline reason must be at least 10 characters.',
            'decline_reason.max' => 'The decline reason cannot exceed 500 characters.',
        ]);

        try {
            DB::beginTransaction();

            // Update the RIS status to declined
            $risSlip->update([
                'status' => RisStatus::DECLINED,
                'declined_by' => Auth::id(),
                'declined_at' => now(),
                'decline_reason' => trim($validated['decline_reason']),
            ]);

            DB::commit();

            // Broadcast events for real-time updates
            broadcast(new RequisitionStatusUpdated($risSlip, 'declined'));

            // Notify the requester about the decline
            if ($risSlip->requested_by) {
                broadcast(new UserNotificationUpdated(
                    $risSlip->requested_by,
                    'requisition_declined',
                    [
                        'ris_no' => $risSlip->ris_no,
                        'reason' => $validated['decline_reason'],
                        'ris_id' => $risSlip->ris_id,
                        'declined_by' => Auth::user()->name,
                        'declined_at' => now()->format('M d, Y h:i A')
                    ]
                ));
            }

            \Log::info('RIS declined successfully', [
                'ris_id' => $risSlip->ris_id,
                'ris_no' => $risSlip->ris_no,
                'declined_by' => Auth::id(),
                'declined_by_name' => Auth::user()->name,
                'reason' => $validated['decline_reason']
            ]);

            return back()->with('success', "RIS #{$risSlip->ris_no} has been declined successfully. The requester has been notified with your reason.");

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error declining RIS', [
                'ris_id' => $risSlip->ris_id,
                'ris_no' => $risSlip->ris_no,
                'declined_by' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'An error occurred while declining the RIS. Please try again or contact support.');
        }
    }

    public function issue(Request $request, RisSlip $risSlip)
    {
        if ($risSlip->status !== 'approved') {
            return back()->with('error', 'This RIS cannot be issued.');
        }

        // Enhanced validation with proper business logic
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:ris_items,item_id',
            'items.*.quantity_issued' => 'required|integer|min:0',
            'items.*.remarks' => 'nullable|string|max:500',
            'received_by' => 'required|exists:users,id',  // Made required
            'signature_type' => 'required|in:esign,sgd',
        ]);

        return DB::transaction(function() use ($validated, $risSlip, $request) {
            $hasIssuedItems = false;
            $validationErrors = [];

            // PRE-VALIDATION: Check all items before processing
            foreach ($validated['items'] as $itemData) {
                $risItem = RisItem::findOrFail($itemData['item_id']);

                // Get current stock availability
                $totalAvailable = SupplyStock::where('supply_id', $risItem->supply_id)
                    ->where('status', 'available')
                    ->sum('quantity_on_hand');

                $requestedQty = $risItem->quantity_requested;
                $issueQty = $itemData['quantity_issued'];

                // BUSINESS RULE VALIDATION:
                // 1. If stock is available, minimum issue quantity is 1
                // 2. Maximum issue quantity is min(requested, available)
                // 3. Zero is only allowed when no stock is available

                if ($totalAvailable > 0) {
                    // Stock is available - minimum must be 1
                    if ($issueQty < 1) {
                        $validationErrors[] = "Item '{$risItem->supply->item_name}' has {$totalAvailable} units available. Minimum issue quantity is 1. Cannot issue 0 for approved items with available stock.";
                        continue;
                    }

                    $maxAllowedQty = min($totalAvailable, $requestedQty);
                    if ($issueQty > $maxAllowedQty) {
                        $validationErrors[] = "Item '{$risItem->supply->item_name}' - Maximum issue quantity is {$maxAllowedQty} (Available: {$totalAvailable}, Requested: {$requestedQty}).";
                        continue;
                    }
                } else {
                    // No stock available - only 0 is allowed
                    if ($issueQty > 0) {
                        $validationErrors[] = "Item '{$risItem->supply->item_name}' has no stock available. Cannot issue {$issueQty} units.";
                        continue;
                    }
                }

                if ($issueQty > 0) {
                    $hasIssuedItems = true;
                }
            }

            // Return validation errors if any
            if (!empty($validationErrors)) {
                return back()->withErrors(['issue_validation' => $validationErrors])
                           ->with('error', 'Please correct the issue quantities before proceeding.')
                           ->withInput();
            }

            // Business rule: At least one item must be issued
            if (!$hasIssuedItems) {
                return back()->with('error', 'You must issue at least one item. Cannot process issuance with all quantities set to zero.');
            }

            // Process each item (existing logic but with better logging)
            foreach ($validated['items'] as $itemData) {
                $risItem = RisItem::findOrFail($itemData['item_id']);
                $issueQty = $itemData['quantity_issued'];

                // Update RIS item
                $risItem->update([
                    'quantity_issued' => $issueQty,
                    'remarks' => $itemData['remarks'] ?? null,
                ]);

                // Only process stock deduction if quantity > 0
                if ($issueQty > 0) {
                    // Get all available stocks for this supply (FIFO approach)
                    $matchingStocks = SupplyStock::where('supply_id', $risItem->supply_id)
                        ->where('status', 'available')
                        ->where('quantity_on_hand', '>', 0)
                        ->orderBy('expiry_date', 'asc') // Use FIFO approach - oldest stock first
                        ->orderBy('created_at', 'asc')   // If same expiry, use oldest entry first
                        ->get();

                    // Calculate current balance before issuance
                    $currentBalance = SupplyStock::where('supply_id', $risItem->supply_id)
                        ->where('status', 'available')
                        ->sum('quantity_on_hand');

                    // Process the issuance from stocks
                    $remainingQuantity = $issueQty;
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

                        // Create the transaction using direct DB query
                        $transaction = DB::table('supply_transactions')->insertGetId([
                            'supply_id' => $risItem->supply_id,
                            'transaction_type' => 'issue',
                            'transaction_date' => now()->toDateString(),
                            'reference_no' => $risSlip->ris_no,
                            'quantity' => $qtyToDeduct,
                            'unit_cost' => $stock->unit_cost,
                            'total_cost' => $stockItemCost,
                            'department_id' => $risSlip->division,
                            'remarks' => "Issued via RIS #{$risSlip->ris_no}" . ($itemData['remarks'] ? " - {$itemData['remarks']}" : ""),
                            'balance_quantity' => $newBalance,
                            'user_id' => Auth::id(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        // Set up the many-to-many relationships
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
                        $currentBalance = $newBalance;
                    }

                    // Log if there was insufficient stock to fulfill the complete request
                    if ($remainingQuantity > 0) {
                        \Log::warning("Insufficient stock for complete fulfillment", [
                            'ris_no' => $risSlip->ris_no,
                            'supply_id' => $risItem->supply_id,
                            'item_name' => $risItem->supply->item_name,
                            'requested' => $issueQty,
                            'fulfilled' => $issueQty - $remainingQuantity,
                            'shortfall' => $remainingQuantity
                        ]);
                    }
                } else {
                    // Log items that couldn't be issued (for audit trail)
                    \Log::info("Item not issued due to insufficient stock", [
                        'ris_no' => $risSlip->ris_no,
                        'supply_id' => $risItem->supply_id,
                        'item_name' => $risItem->supply->item_name,
                        'reason' => 'No stock available'
                    ]);
                }
            }

            // Update the RIS status
            $risSlip->update([
                'status' => 'posted',
                'issued_by' => Auth::id(),
                'issued_at' => now(),
                'issuer_signature_type' => $validated['signature_type'],
                'received_by' => $request->received_by,
            ]);

            // Broadcast events
            broadcast(new RequisitionStatusUpdated($risSlip, 'issued'));

            if ($request->received_by) {
                broadcast(new UserNotificationUpdated(
                    $request->received_by,
                    'supplies_ready',
                    ['ris_no' => $risSlip->ris_no, 'ris_id' => $risSlip->ris_id]
                ));
            }

            // Enhanced success logging
            $issuedItemsCount = collect($validated['items'])->where('quantity_issued', '>', 0)->count();
            $totalItemsCount = count($validated['items']);

            \Log::info('RIS issued successfully', [
                'ris_id' => $risSlip->ris_id,
                'ris_no' => $risSlip->ris_no,
                'issued_by' => Auth::id(),
                'issued_by_name' => Auth::user()->name,
                'received_by' => $request->received_by,
                'total_items' => $totalItemsCount,
                'issued_items' => $issuedItemsCount,
                'signature_type' => $validated['signature_type']
            ]);

            return redirect()->route('ris.show', $risSlip)
                            ->with('success', "Supplies issued successfully! {$issuedItemsCount} out of {$totalItemsCount} items processed.");
        });
    }

    public function receive(Request $request, RisSlip $risSlip)
    {
        // Validate the signature type
        $validated = $request->validate([
            'signature_type' => 'required|in:esign,sgd',
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
            'receiver_signature_type' => $validated['signature_type'],
        ]);

        // Broadcast completion event
        broadcast(new RequisitionStatusUpdated($risSlip, 'completed'));

        if ($risSlip->received_by) {
            broadcast(new UserNotificationUpdated(
                $risSlip->received_by,
                'supplies_received',
                ['ris_no' => $risSlip->ris_no]
            ));
        }

        return back()->with('success', 'Supplies received successfully.');
    }

    /**
     * Print the RIS form.
     */
    public function print(RisSlip $risSlip)
    {
        $risSlip->load(['department', 'requester', 'approver', 'issuer', 'receiver', 'decliner', 'items.supply']);
        return view('ris.print', compact('risSlip'));
    }

    /**
     * Export RIS to Excel with E-Signature Images
     */
    public function exportExcel(RisSlip $risSlip)
    {
        // Create Excel file
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set page setup
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_LETTER);
        $sheet->getPageMargins()->setTop(0.5)->setRight(0.5)->setLeft(0.5)->setBottom(0.5);

        // Appendix 63 (top right)
        $sheet->setCellValue('H1', 'Appendix 63');
        $sheet->getStyle('H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('H1')->getFont()->setItalic(true)->setSize(15);

        // Title
        $sheet->mergeCells('A3:H3');
        $sheet->setCellValue('A3', 'REQUISITION AND ISSUE SLIP');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $currentRow = 5;

        // Entity Information Section (NO BORDERS - just underlines like the template)
        // First row - Entity Name and Fund Cluster
        $sheet->setCellValue("A{$currentRow}", "Entity Name:");
        $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true);
        $sheet->mergeCells("B{$currentRow}:F{$currentRow}");
        $sheet->setCellValue("B{$currentRow}", $risSlip->entity_name);
        // Add underline only (no full borders)
        $sheet->getStyle("B{$currentRow}:F{$currentRow}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

        $sheet->setCellValue("G{$currentRow}", "Fund Cluster:");
        $sheet->getStyle("G{$currentRow}")->getFont()->setBold(true);
        $sheet->setCellValue("H{$currentRow}", $risSlip->fund_cluster);
        // Add underline only (no full borders)
        $sheet->getStyle("H{$currentRow}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

        $currentRow++;

        // Second row - Division and Responsibility Center Code (with borders)
        $sheet->setCellValue("A{$currentRow}", "Division:");
        $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true);
        $sheet->mergeCells("B{$currentRow}:D{$currentRow}");
        $sheet->setCellValue("B{$currentRow}", $risSlip->department->name ?? 'N/A');
        $sheet->getStyle("A{$currentRow}:D{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $sheet->setCellValue("E{$currentRow}", "Responsibility Center Code:");
        $sheet->getStyle("E{$currentRow}")->getFont()->setBold(true);
        $sheet->mergeCells("F{$currentRow}:H{$currentRow}");
        $sheet->setCellValue("F{$currentRow}", $risSlip->responsibility_center_code ?? '');
        $sheet->getStyle("E{$currentRow}:H{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $currentRow++;

        // Third row - Office and RIS No. (with borders)
        $sheet->setCellValue("A{$currentRow}", "Office:");
        $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true);
        $sheet->mergeCells("B{$currentRow}:D{$currentRow}");
        $sheet->setCellValue("B{$currentRow}", $risSlip->office ?? '');
        $sheet->getStyle("A{$currentRow}:D{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $sheet->setCellValue("E{$currentRow}", "RIS No.:");
        $sheet->getStyle("E{$currentRow}")->getFont()->setBold(true);
        $sheet->mergeCells("F{$currentRow}:H{$currentRow}");
        $sheet->setCellValue("F{$currentRow}", $risSlip->ris_no);
        $sheet->getStyle("E{$currentRow}:H{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $currentRow ++; // Add space

        // Main items table headers
        // First header row with merged sections
        $sheet->mergeCells("A{$currentRow}:D{$currentRow}");
        $sheet->setCellValue("A{$currentRow}", "Requisition");
        $sheet->mergeCells("E{$currentRow}:F{$currentRow}");
        $sheet->setCellValue("E{$currentRow}", "Stock Available?");
        $sheet->mergeCells("G{$currentRow}:H{$currentRow}");
        $sheet->setCellValue("G{$currentRow}", "Issue");

        // Style first header row
        $headerRange = "A{$currentRow}:H{$currentRow}";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'F2F2F2']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);

        $currentRow++;

        // Second header row with detailed columns
        $subHeaders = [
            'A' => "Stock No.",
            'B' => "Unit",
            'C' => "Description",
            'D' => "Quantity",
            'E' => "Yes",
            'F' => "No",
            'G' => "Quantity",
            'H' => "Remarks"
        ];

        foreach ($subHeaders as $col => $header) {
            $sheet->setCellValue("{$col}{$currentRow}", $header);
        }

        // Style second header row
        $subHeaderRange = "A{$currentRow}:H{$currentRow}";
        $sheet->getStyle($subHeaderRange)->applyFromArray([
            'font' => ['bold' => true, 'size' => 10],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'F2F2F2']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);

        $sheet->getRowDimension($currentRow - 1)->setRowHeight(25);
        $sheet->getRowDimension($currentRow)->setRowHeight(25);

        // Data rows
        $currentRow++;

        foreach ($risSlip->items as $item) {
            $sheet->setCellValue("A{$currentRow}", $item->supply->stock_no ?? 'N/A');
            $sheet->setCellValue("B{$currentRow}", $item->supply->unit_of_measurement ?? 'N/A');

            // Description (combine item name and description)
            $parts = [
                $item->supply->item_name,
                $item->supply->description
            ];
            $display = collect($parts)->filter()->join(', ');
            $sheet->setCellValue("C{$currentRow}", $display ?: 'N/A');

            $sheet->setCellValue("D{$currentRow}", $item->quantity_requested);

            // Stock Available columns (Yes/No)
            $sheet->setCellValue("E{$currentRow}", $item->stock_available ? '✓' : '');
            $sheet->setCellValue("F{$currentRow}", !$item->stock_available ? '✓' : '');

            // Issue quantity and remarks
            $sheet->setCellValue("G{$currentRow}", $item->quantity_issued ?? '');
            $sheet->setCellValue("H{$currentRow}", $item->remarks ?? '');

            // Format numbers
            $sheet->getStyle("D{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("G{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');

            // Apply borders
            $sheet->getStyle("A{$currentRow}:H{$currentRow}")->getBorders()
                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Alignment
            $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("D{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E{$currentRow}:F{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("G{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("H{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

            $currentRow++;
        }

        // // Add empty rows to match template format (minimum 10 rows total)
        // $emptyRowsToAdd = max(10 - count($risSlip->items), 0);
        // for ($i = 0; $i < $emptyRowsToAdd; $i++) {
        //     $sheet->getStyle("A{$currentRow}:H{$currentRow}")->getBorders()
        //         ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        //     $currentRow++;
        // }

        // $currentRow += 1; // Add small space before purpose

        // Purpose section
        $sheet->setCellValue("A{$currentRow}", "Purpose:");
        $sheet->getStyle("A{$currentRow}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'F2F2F2']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);

        $sheet->mergeCells("B{$currentRow}:H{$currentRow}");
        $sheet->getStyle("B{$currentRow}:H{$currentRow}")->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'F2F2F2']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);

        $currentRow++;

        // Purpose content (smaller - only 2 rows)
        $sheet->mergeCells("A{$currentRow}:H" . ($currentRow + 1));
        $sheet->setCellValue("A{$currentRow}", $risSlip->purpose);
        $sheet->getStyle("A{$currentRow}:H" . ($currentRow + 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("A{$currentRow}")->getAlignment()->setVertical(Alignment::VERTICAL_TOP)->setWrapText(true);
        $sheet->getRowDimension($currentRow)->setRowHeight(30);

        $currentRow += 2; // Move past purpose content

        // Signature section (NO gap - directly connected)
        $signatureHeaders = ['Requested by:', 'Approved by:', 'Issued by:', 'Received by:'];

        // Header row with labels
        $sheet->mergeCells("A{$currentRow}:B{$currentRow}");
        $sheet->setCellValue("A{$currentRow}", "");
        $sheet->getStyle("A{$currentRow}:B{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $sheet->setCellValue("C{$currentRow}", "Requested by:");
        $sheet->getStyle("C{$currentRow}")->getFont()->setBold(true);
        $sheet->getStyle("C{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("C{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("D{$currentRow}:E{$currentRow}");
        $sheet->setCellValue("D{$currentRow}", "Approved by:");
        $sheet->getStyle("D{$currentRow}:E{$currentRow}")->getFont()->setBold(true);
        $sheet->getStyle("D{$currentRow}:E{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("D{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("F{$currentRow}:G{$currentRow}");
        $sheet->setCellValue("F{$currentRow}", "Issued by:");
        $sheet->getStyle("F{$currentRow}:G{$currentRow}")->getFont()->setBold(true);
        $sheet->getStyle("F{$currentRow}:G{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("F{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue("H{$currentRow}", "Received by:");
        $sheet->getStyle("H{$currentRow}")->getFont()->setBold(true);
        $sheet->getStyle("H{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("H{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $currentRow++;

        // Signature row with label in columns A-B
        $sheet->mergeCells("A{$currentRow}:B{$currentRow}");
        $sheet->setCellValue("A{$currentRow}", "Signature :");
        $sheet->getStyle("A{$currentRow}:B{$currentRow}")->getFont()->setBold(true);
        $sheet->getStyle("A{$currentRow}:B{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Set higher row height for signature images
        $sheet->getRowDimension($currentRow)->setRowHeight(50);

        // Apply borders to all signature cells first
        $sheet->getStyle("C{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("D{$currentRow}:E{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("F{$currentRow}:G{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("H{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Requested by (Column C) - IMAGE OR SGD
        if ($risSlip->requester && $risSlip->requester_signature_type == 'esign' && $risSlip->requester->signature_path) {
            try {
                $signaturePath = storage_path('app/public/' . $risSlip->requester->signature_path);
                if (file_exists($signaturePath)) {
                    $drawing = new Drawing();
                    $drawing->setName('Requester Signature');
                    $drawing->setDescription('Requester Signature');
                    $drawing->setPath($signaturePath);
                    $drawing->setHeight(35);
                    $drawing->setOffsetX(10);
                    $drawing->setOffsetY(5);
                    $drawing->setCoordinates("C{$currentRow}");
                    $drawing->setWorksheet($sheet);
                } else {
                    $sheet->setCellValue("C{$currentRow}", "SGD");
                    $sheet->getStyle("C{$currentRow}")->getFont()->setItalic(true)->setBold(true);
                }
            } catch (Exception $e) {
                $sheet->setCellValue("C{$currentRow}", "SGD");
                $sheet->getStyle("C{$currentRow}")->getFont()->setItalic(true)->setBold(true);
            }
        } elseif ($risSlip->requester) {
            $sheet->setCellValue("C{$currentRow}", "SGD");
            $sheet->getStyle("C{$currentRow}")->getFont()->setItalic(true)->setBold(true);
        }
        $sheet->getStyle("C{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        // Approved by (Columns D-E) - IMAGE OR SGD
        $sheet->mergeCells("D{$currentRow}:E{$currentRow}");
        if ($risSlip->approved_by && $risSlip->approver_signature_type == 'esign' && $risSlip->approver && $risSlip->approver->signature_path) {
            try {
                $signaturePath = storage_path('app/public/' . $risSlip->approver->signature_path);
                if (file_exists($signaturePath)) {
                    $drawing = new Drawing();
                    $drawing->setName('Approver Signature');
                    $drawing->setDescription('Approver Signature');
                    $drawing->setPath($signaturePath);
                    $drawing->setHeight(35);
                    $drawing->setOffsetX(10);
                    $drawing->setOffsetY(5);
                    $drawing->setCoordinates("D{$currentRow}");
                    $drawing->setWorksheet($sheet);
                } else {
                    $sheet->setCellValue("D{$currentRow}", "SGD");
                    $sheet->getStyle("D{$currentRow}")->getFont()->setItalic(true)->setBold(true);
                }
            } catch (Exception $e) {
                $sheet->setCellValue("D{$currentRow}", "SGD");
                $sheet->getStyle("D{$currentRow}")->getFont()->setItalic(true)->setBold(true);
            }
        } elseif ($risSlip->approved_by) {
            $sheet->setCellValue("D{$currentRow}", "SGD");
            $sheet->getStyle("D{$currentRow}")->getFont()->setItalic(true)->setBold(true);
        }
        $sheet->getStyle("D{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        // Issued by (Columns F-G) - IMAGE OR SGD
        $sheet->mergeCells("F{$currentRow}:G{$currentRow}");
        if ($risSlip->issued_by && $risSlip->issuer_signature_type == 'esign' && $risSlip->issuer && $risSlip->issuer->signature_path) {
            try {
                $signaturePath = storage_path('app/public/' . $risSlip->issuer->signature_path);
                if (file_exists($signaturePath)) {
                    $drawing = new Drawing();
                    $drawing->setName('Issuer Signature');
                    $drawing->setDescription('Issuer Signature');
                    $drawing->setPath($signaturePath);
                    $drawing->setHeight(35);
                    $drawing->setOffsetX(10);
                    $drawing->setOffsetY(5);
                    $drawing->setCoordinates("F{$currentRow}");
                    $drawing->setWorksheet($sheet);
                } else {
                    $sheet->setCellValue("F{$currentRow}", "SGD");
                    $sheet->getStyle("F{$currentRow}")->getFont()->setItalic(true)->setBold(true);
                }
            } catch (Exception $e) {
                $sheet->setCellValue("F{$currentRow}", "SGD");
                $sheet->getStyle("F{$currentRow}")->getFont()->setItalic(true)->setBold(true);
            }
        } elseif ($risSlip->issued_by) {
            $sheet->setCellValue("F{$currentRow}", "SGD");
            $sheet->getStyle("F{$currentRow}")->getFont()->setItalic(true)->setBold(true);
        }
        $sheet->getStyle("F{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        // Received by (Column H) - IMAGE OR SGD
        if ($risSlip->received_at && $risSlip->receiver_signature_type == 'esign' && $risSlip->receiver && $risSlip->receiver->signature_path) {
            try {
                $signaturePath = storage_path('app/public/' . $risSlip->receiver->signature_path);
                if (file_exists($signaturePath)) {
                    $drawing = new Drawing();
                    $drawing->setName('Receiver Signature');
                    $drawing->setDescription('Receiver Signature');
                    $drawing->setPath($signaturePath);
                    $drawing->setHeight(35);
                    $drawing->setOffsetX(10);
                    $drawing->setOffsetY(5);
                    $drawing->setCoordinates("H{$currentRow}");
                    $drawing->setWorksheet($sheet);
                } else {
                    $sheet->setCellValue("H{$currentRow}", "SGD");
                    $sheet->getStyle("H{$currentRow}")->getFont()->setItalic(true)->setBold(true);
                }
            } catch (Exception $e) {
                $sheet->setCellValue("H{$currentRow}", "SGD");
                $sheet->getStyle("H{$currentRow}")->getFont()->setItalic(true)->setBold(true);
            }
        } elseif ($risSlip->received_at) {
            $sheet->setCellValue("H{$currentRow}", "SGD");
            $sheet->getStyle("H{$currentRow}")->getFont()->setItalic(true)->setBold(true);
        }
        $sheet->getStyle("H{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        $currentRow++;

        // Printed Name row
        $sheet->mergeCells("A{$currentRow}:B{$currentRow}");
        $sheet->setCellValue("A{$currentRow}", "Printed Name :");
        $sheet->getStyle("A{$currentRow}:B{$currentRow}")->getFont()->setBold(true);
        $sheet->getStyle("A{$currentRow}:B{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $names = [
            $risSlip->requester->name ?? '____________________',
            $risSlip->approved_by ? optional($risSlip->approver)->name : '____________________',
            $risSlip->issued_by ? optional($risSlip->issuer)->name : '____________________',
            $risSlip->received_by ? optional($risSlip->receiver)->name : '____________________'
        ];

        // Requested by name (Column C)
        $sheet->setCellValue("C{$currentRow}", $names[0]);
        $sheet->getStyle("C{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("C{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("C{$currentRow}")->getFont()->setBold(true);

        // Approved by name (Columns D-E)
        $sheet->mergeCells("D{$currentRow}:E{$currentRow}");
        $sheet->setCellValue("D{$currentRow}", $names[1]);
        $sheet->getStyle("D{$currentRow}:E{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("D{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D{$currentRow}")->getFont()->setBold(true);

        // Issued by name (Columns F-G)
        $sheet->mergeCells("F{$currentRow}:G{$currentRow}");
        $sheet->setCellValue("F{$currentRow}", $names[2]);
        $sheet->getStyle("F{$currentRow}:G{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("F{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("F{$currentRow}")->getFont()->setBold(true);

        // Received by name (Column H)
        $sheet->setCellValue("H{$currentRow}", $names[3]);
        $sheet->getStyle("H{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("H{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("H{$currentRow}")->getFont()->setBold(true);

        $currentRow++;

        // Designation row
        $sheet->mergeCells("A{$currentRow}:B{$currentRow}");
        $sheet->setCellValue("A{$currentRow}", "Designation :");
        $sheet->getStyle("A{$currentRow}:B{$currentRow}")->getFont()->setBold(true);
        $sheet->getStyle("A{$currentRow}:B{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $designations = [
            optional($risSlip->requester)->designation->name ?? 'Designation',
            $risSlip->approved_by ? optional($risSlip->approver)->designation->name ?? 'Designation' : 'Designation',
            $risSlip->issued_by ? optional($risSlip->issuer)->designation->name ?? 'Designation' : 'Designation',
            $risSlip->received_by ? optional($risSlip->receiver)->designation->name ?? 'Designation' : 'Designation'
        ];

        // Requested by designation (Column C)
        $sheet->setCellValue("C{$currentRow}", $designations[0]);
        $sheet->getStyle("C{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("C{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Approved by designation (Columns D-E)
        $sheet->mergeCells("D{$currentRow}:E{$currentRow}");
        $sheet->setCellValue("D{$currentRow}", $designations[1]);
        $sheet->getStyle("D{$currentRow}:E{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("D{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Issued by designation (Columns F-G)
        $sheet->mergeCells("F{$currentRow}:G{$currentRow}");
        $sheet->setCellValue("F{$currentRow}", $designations[2]);
        $sheet->getStyle("F{$currentRow}:G{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("F{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Received by designation (Column H)
        $sheet->setCellValue("H{$currentRow}", $designations[3]);
        $sheet->getStyle("H{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("H{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $currentRow++;

        // Date row
        $sheet->mergeCells("A{$currentRow}:B{$currentRow}");
        $sheet->setCellValue("A{$currentRow}", "Date :");
        $sheet->getStyle("A{$currentRow}:B{$currentRow}")->getFont()->setBold(true);
        $sheet->getStyle("A{$currentRow}:B{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $dates = [
            $risSlip->created_at->format('M d, Y'),
            $risSlip->approved_at ? $risSlip->approved_at->format('M d, Y') : '____________________',
            $risSlip->issued_at ? $risSlip->issued_at->format('M d, Y') : '____________________',
            $risSlip->received_at ? $risSlip->received_at->format('M d, Y') : '____________________'
        ];

        // Requested by date (Column C)
        $sheet->setCellValue("C{$currentRow}", $dates[0]);
        $sheet->getStyle("C{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("C{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Approved by date (Columns D-E)
        $sheet->mergeCells("D{$currentRow}:E{$currentRow}");
        $sheet->setCellValue("D{$currentRow}", $dates[1]);
        $sheet->getStyle("D{$currentRow}:E{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("D{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Issued by date (Columns F-G)
        $sheet->mergeCells("F{$currentRow}:G{$currentRow}");
        $sheet->setCellValue("F{$currentRow}", $dates[2]);
        $sheet->getStyle("F{$currentRow}:G{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("F{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Received by date (Column H)
        $sheet->setCellValue("H{$currentRow}", $dates[3]);
        $sheet->getStyle("H{$currentRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("H{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Adjust column widths
        $sheet->getColumnDimension('A')->setWidth(12);  // Stock No / Labels
        $sheet->getColumnDimension('B')->setWidth(10);  // Unit
        $sheet->getColumnDimension('C')->setWidth(30);  // Description / Requested by
        $sheet->getColumnDimension('D')->setWidth(10);  // Quantity / Approved by (part 1)
        $sheet->getColumnDimension('E')->setWidth(10);  // Yes / Approved by (part 2)
        $sheet->getColumnDimension('F')->setWidth(10);  // No / Issued by (part 1)
        $sheet->getColumnDimension('G')->setWidth(10);  // Issue Quantity / Issued by (part 2)
        $sheet->getColumnDimension('H')->setWidth(18);  // Remarks / Received by (wider to prevent overflow)

        // Write file
        $writer = new Xlsx($spreadsheet);
        $filename = 'RIS_' . $risSlip->ris_no . '_' . $risSlip->created_at->format('Y_m_d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }


    /**
     * Store manually created RIS (for historical data) - ENHANCED VERSION
     */
    public function storeManual(Request $request, ReferenceNumberService $referenceNumberService)
    {
        // Only admin and CAO can create manual entries
        if (!auth()->user()->hasRole(['admin', 'cao'])) {
            return back()->with('error', 'Unauthorized to create manual RIS entries.');
        }

        // Enhanced validation for manual entries
        $validated = $request->validate([
            'is_manual_entry' => 'required|boolean',
            'ris_date' => [
                'required',
                'date',
                'before_or_equal:today',
                'after_or_equal:' . now()->subYears(5)->format('Y-m-d')
            ],
            'reference_source' => 'nullable|string|max:255',
            'entity_name' => 'required|string|max:255',
            'division' => 'required|exists:departments,id',
            'office' => 'nullable|string|max:255',
            'fund_cluster' => 'nullable|in:101,151',
            'responsibility_center_code' => 'nullable|string|max:50',
            'requested_by' => 'required|exists:users,id',
            'purpose' => 'required|string|max:1000',
            'final_status' => 'required|in:completed,posted,declined',
            'decline_reason' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.supply_id' => 'required|exists:supplies,supply_id',
            'items.*.quantity_requested' => 'required|integer|min:1',
            'items.*.quantity_issued' => 'nullable|integer|min:0',
            'items.*.remarks' => 'nullable|string|max:500',
        ]);

        // Additional validation for decline reason
        if ($validated['final_status'] === 'declined' && empty($validated['decline_reason'])) {
            return back()->withErrors([
                'decline_reason' => 'Decline reason is required when status is declined.'
            ])->withInput();
        }

        try {
            return DB::transaction(function() use ($validated, $referenceNumberService) {

                // CRITICAL VALIDATION: Check if all supplies have valid IAR/Stock
                $stockValidationErrors = [];

                foreach ($validated['items'] as $index => $item) {
                    $supplyId = $item['supply_id'];
                    $requestedQty = $item['quantity_requested'];

                    // Check if supply has any stock (IAR exists)
                    $stock = SupplyStock::where('supply_id', $supplyId)
                        ->where('status', 'available')
                        ->where('quantity_on_hand', '>', 0)
                        ->first();

                    if (!$stock) {
                        $supply = Supply::find($supplyId);
                        $stockValidationErrors[] = [
                            'supply_name' => $supply ? $supply->item_name : 'Unknown',
                            'message' => 'No IAR found for this supply. Cannot request items without inventory record.'
                        ];
                        continue;
                    }

                    // For historical data, we still validate against available quantity
                    $pendingRequested = RisItem::where('supply_id', $supplyId)
                        ->whereHas('risSlip', function($q) {
                            $q->whereIn('status', ['draft', 'approved']);
                        })
                        ->sum('quantity_requested');

                    $currentAvailable = max(0, $stock->quantity_on_hand - $pendingRequested);

                    if ($requestedQty > $currentAvailable) {
                        $supply = Supply::find($supplyId);
                        $stockValidationErrors[] = [
                            'supply_name' => $supply ? $supply->item_name : 'Unknown',
                            'requested' => $requestedQty,
                            'available' => $currentAvailable,
                            'message' => "Insufficient stock. Available: {$currentAvailable}, Requested: {$requestedQty}"
                        ];
                    }
                }

                // Return validation errors if any
                if (!empty($stockValidationErrors)) {
                    return back()
                        ->withErrors(['stock_validation' => $stockValidationErrors])
                        ->with('error', 'Cannot create RIS: Stock validation failed.')
                        ->withInput();
                }

                // Generate RIS number for the historical date
                $risDate = Carbon::parse($validated['ris_date']);
                $newRisNumber = $this->generateHistoricalRisNumber($risDate);

                // Create the RIS Slip with historical date and manual entry fields
                $risSlip = RisSlip::create([
                    'ris_no' => $newRisNumber,
                    'ris_date' => $risDate,
                    'entity_name' => $validated['entity_name'],
                    'division' => $validated['division'],
                    'office' => $validated['office'],
                    'fund_cluster' => $validated['fund_cluster'],
                    'responsibility_center_code' => $validated['responsibility_center_code'],
                    'requested_by' => $validated['requested_by'],
                    'requester_signature_type' => 'sgd', // Manual entries use SGD
                    'purpose' => $validated['purpose'],
                    'status' => 'draft', // Start as draft, then update based on final_status

                    // MANUAL ENTRY FIELDS
                    'is_manual_entry' => true,
                    'reference_source' => $validated['reference_source'],
                    'manual_entry_by' => auth()->id(),
                    'manual_entry_at' => now(),
                    'manual_entry_notes' => "Historical entry for {$risDate->format('M d, Y')}",

                    // Set historical timestamps
                    'created_at' => $risDate,
                    'updated_at' => $risDate,
                ]);

                // Create RIS Items
                foreach ($validated['items'] as $item) {
                    RisItem::create([
                        'ris_id' => $risSlip->ris_id,
                        'supply_id' => $item['supply_id'],
                        'quantity_requested' => $item['quantity_requested'],
                        'quantity_issued' => $item['quantity_issued'] ?? 0,
                        'stock_available' => true, // We validated stock exists
                        'remarks' => $item['remarks'],
                    ]);
                }

                // Apply historical status transitions
                $this->applyHistoricalStatus($risSlip, $validated, $risDate);

                // Log manual entry for audit
                Log::info('Manual RIS entry created', [
                    'ris_no' => $newRisNumber,
                    'ris_date' => $risDate->format('Y-m-d'),
                    'created_by' => auth()->id(),
                    'created_by_name' => auth()->user()->name,
                    'requested_by' => $validated['requested_by'],
                    'final_status' => $validated['final_status'],
                    'reference_source' => $validated['reference_source'],
                    'items_count' => count($validated['items']),
                    'entity_name' => $validated['entity_name'],
                    'purpose' => substr($validated['purpose'], 0, 100) . '...'
                ]);

                return redirect()
                    ->route('ris.index')
                    ->with('success', "Manual RIS {$newRisNumber} created successfully for date {$risDate->format('M d, Y')}.");
            });

        } catch (\Exception $e) {
            Log::error('Failed to create manual RIS', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'ris_date' => $validated['ris_date'] ?? null,
                'reference_source' => $validated['reference_source'] ?? null
            ]);

            return back()
                ->with('error', 'Failed to create manual RIS: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Generate unique RIS number for historical dates - handles gaps properly
     */
    private function generateHistoricalRisNumber(Carbon $date): string
    {
        $year = $date->format('Y');
        $month = $date->format('m');

        // Get all existing RIS numbers for that month, sorted by number
        $existingNumbers = RisSlip::where('ris_no', 'like', "RIS {$year}-{$month}-%")
            ->pluck('ris_no')
            ->map(function($risNo) {
                $parts = explode('-', $risNo);
                return intval($parts[2] ?? 0);
            })
            ->sort()
            ->values();

        // Find the next available number (handles gaps)
        $next = 1;
        foreach ($existingNumbers as $num) {
            if ($num == $next) {
                $next++;
            } else {
                break; // Found a gap
            }
        }

        return sprintf("RIS %s-%s-%03d", $year, $month, $next);
    }

    /**
     * Apply historical status to manually created RIS - ENHANCED VERSION
     */
    private function applyHistoricalStatus(RisSlip $risSlip, array $validated, Carbon $risDate)
    {
        $currentUserId = auth()->id();

        switch ($validated['final_status']) {
            case 'declined':
                $risSlip->update([
                    'status' => RisStatus::DECLINED,
                    'declined_by' => $currentUserId,
                    'declined_at' => $risDate,
                    'decline_reason' => $validated['decline_reason'],
                    'updated_at' => $risDate,
                ]);

                // Log decline
                Log::info('Manual RIS declined', [
                    'ris_no' => $risSlip->ris_no,
                    'reason' => $validated['decline_reason'],
                    'declined_by' => auth()->user()->name
                ]);
                break;

            case 'posted':
                // Approved -> Issued (Pending Receipt)
                $risSlip->update([
                    'status' => RisStatus::POSTED,
                    'approved_by' => $currentUserId,
                    'approved_at' => $risDate,
                    'approver_signature_type' => 'sgd',
                    'issued_by' => $currentUserId,
                    'issued_at' => $risDate,
                    'issuer_signature_type' => 'sgd',
                    'updated_at' => $risDate,
                ]);

                // Process stock deductions for issued items
                $this->processHistoricalStockDeductions($risSlip, $risDate);

                Log::info('Manual RIS issued', [
                    'ris_no' => $risSlip->ris_no,
                    'issued_by' => auth()->user()->name
                ]);
                break;

            case 'completed':
                // Approved -> Issued -> Received (Completed)
                $risSlip->update([
                    'status' => RisStatus::POSTED,
                    'approved_by' => $currentUserId,
                    'approved_at' => $risDate,
                    'approver_signature_type' => 'sgd',
                    'issued_by' => $currentUserId,
                    'issued_at' => $risDate,
                    'issuer_signature_type' => 'sgd',
                    'received_by' => $validated['requested_by'], // Set receiver as the requester
                    'received_at' => $risDate,
                    'receiver_signature_type' => 'sgd',
                    'updated_at' => $risDate,
                ]);

                // Process stock deductions for completed items
                $this->processHistoricalStockDeductions($risSlip, $risDate);

                Log::info('Manual RIS completed', [
                    'ris_no' => $risSlip->ris_no,
                    'completed_by' => auth()->user()->name,
                    'received_by' => User::find($validated['requested_by'])->name ?? 'Unknown'
                ]);
                break;
        }
    }

    /**
     * Process stock deductions for historical manual entries
     */
    private function processHistoricalStockDeductions(RisSlip $risSlip, Carbon $risDate)
    {
        foreach ($risSlip->items as $item) {
            $issueQty = $item->quantity_issued;

            if ($issueQty > 0) {
                // Get available stocks (FIFO approach)
                $matchingStocks = SupplyStock::where('supply_id', $item->supply_id)
                    ->where('status', 'available')
                    ->where('quantity_on_hand', '>', 0)
                    ->orderBy('expiry_date', 'asc')
                    ->orderBy('created_at', 'asc')
                    ->get();

                $remainingQuantity = $issueQty;
                $totalCost = 0;

                foreach ($matchingStocks as $stock) {
                    if ($remainingQuantity <= 0) break;

                    $qtyToDeduct = min($remainingQuantity, $stock->quantity_on_hand);

                    // Update stock quantity
                    $stock->quantity_on_hand -= $qtyToDeduct;
                    $stockItemCost = $qtyToDeduct * $stock->unit_cost;
                    $totalCost += $stockItemCost;

                    if ($stock->quantity_on_hand <= 0) {
                        $stock->status = 'depleted';
                    }
                    $stock->save();

                    // Create historical transaction
                    $transaction = SupplyTransaction::create([
                        'supply_id' => $item->supply_id,
                        'transaction_type' => 'issue',
                        'transaction_date' => $risDate->toDateString(),
                        'reference_no' => $risSlip->ris_no,
                        'quantity' => $qtyToDeduct,
                        'unit_cost' => $stock->unit_cost,
                        'total_cost' => $stockItemCost,
                        'department_id' => $risSlip->division,
                        'user_id' => auth()->id(),
                        'remarks' => "Historical issue via RIS #{$risSlip->ris_no}" .
                                   ($item->remarks ? " - {$item->remarks}" : ""),
                        'fund_cluster' => $risSlip->fund_cluster,
                        'balance_quantity' => $stock->quantity_on_hand,
                        'balance_unit_cost' => $stock->unit_cost,
                        'balance_total_cost' => $stock->quantity_on_hand * $stock->unit_cost,
                        'created_at' => $risDate,
                        'updated_at' => $risDate,
                    ]);

                    $remainingQuantity -= $qtyToDeduct;
                }

                if ($remainingQuantity > 0) {
                    Log::warning("Historical RIS: Insufficient stock for complete fulfillment", [
                        'ris_no' => $risSlip->ris_no,
                        'supply_id' => $item->supply_id,
                        'requested' => $issueQty,
                        'fulfilled' => $issueQty - $remainingQuantity,
                        'shortfall' => $remainingQuantity
                    ]);
                }
            }
        }
    }


    /**
     * AJAX: Get all active supplies (even if quantity_on_hand == 0)
     */
    public function getAvailableSupplies(Request $request)
    {
        if (! auth()->user()->hasRole(['admin','cao'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $supplies = Supply::where('is_active', true)
            ->orderBy('item_name')
            ->get()
            ->map(function($supply) {
                // total on‐hand, even if zero
                $available = SupplyStock::getTotalAvailableQuantity($supply->supply_id);
                return [
                    'supply_id'          => $supply->supply_id,
                    'stock_no'           => $supply->stock_no,
                    'item_name'          => $supply->item_name,
                    'description'        => $supply->description,
                    'unit_of_measurement'=> $supply->unit_of_measurement,
                    'available_quantity' => $available,
                ];
            });

        return response()->json(['supplies' => $supplies]);
    }


    /**
     * AJAX: Validate stock availability for manual entry
     */
    public function validateManualStock(Request $request)
    {
        if (!auth()->user()->hasRole(['admin', 'cao'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.supply_id' => 'required|exists:supplies,supply_id',
            'items.*.quantity_requested' => 'required|integer|min:1',
        ]);

        $stockValidationErrors = [];
        $currentAvailability = [];

        foreach ($validated['items'] as $index => $item) {
            $supplyId = $item['supply_id'];
            $requestedQty = $item['quantity_requested'];

            // Check if supply has any stock (IAR exists)
            $stock = SupplyStock::where('supply_id', $supplyId)
                ->where('status', 'available')
                ->where('quantity_on_hand', '>', 0)
                ->first();

            if (!$stock) {
                $supply = Supply::find($supplyId);
                $stockValidationErrors[] = [
                    'supply_id' => $supplyId,
                    'supply_name' => $supply ? $supply->item_name : 'Unknown Item',
                    'message' => 'No IAR found for this supply. Cannot request items without inventory record.',
                    'available' => 0
                ];
                $currentAvailability[$supplyId] = 0;
                continue;
            }

            // Calculate current real-time availability
            $pendingRequested = RisItem::where('supply_id', $supplyId)
                ->whereHas('risSlip', function($q) {
                    $q->whereIn('status', ['draft','approved']);
                })
                ->sum('quantity_requested');

            $currentAvailable = max(0, $stock->quantity_on_hand - $pendingRequested);
            $currentAvailability[$supplyId] = $currentAvailable;

            if ($requestedQty > $currentAvailable) {
                $supply = Supply::find($supplyId);
                $stockValidationErrors[] = [
                    'supply_id' => $supplyId,
                    'supply_name' => $supply ? $supply->item_name : 'Unknown Item',
                    'requested' => $requestedQty,
                    'available' => $currentAvailable,
                    'message' => "Only {$currentAvailable} units available (requested: {$requestedQty})"
                ];
            }
        }

        return response()->json([
            'success' => empty($stockValidationErrors),
            'errors' => $stockValidationErrors,
            'current_availability' => $currentAvailability
        ]);
    }

    /**
     * AJAX → return the next RIS for a given date.
     */
    public function nextRis(Request $request)
    {
        $data = $request->validate([
            'ris_date' => 'required|date',
        ]);

        $date      = Carbon::parse($data['ris_date']);
        // mirror your generateHistoricalRisNumber() logic or use ReferenceNumberService:
        $defaultRis = $this->generateHistoricalRisNumber($date);

        return response()->json(['defaultRis' => $defaultRis]);
    }


}

