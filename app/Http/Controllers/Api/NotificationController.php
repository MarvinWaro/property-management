<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RisSlip;
use Illuminate\Support\Facades\Session;

class NotificationController extends Controller
{
    /**
     * Get the count of pending requisitions by status (ADMIN)
     * [Keep existing getPendingCount method as is]
     */
    public function getPendingCount(Request $request)
    {
        // Check if user is admin
        if ($request->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get counts for different statuses
        $draftCount = RisSlip::where('status', 'draft')->count();
        $approvedCount = RisSlip::where('status', 'approved')->count();

        // Total count that needs attention (not yet issued)
        $totalPending = $draftCount + $approvedCount;

        // Optional: Still track new ones for other features
        $lastViewed = Session::get('last_viewed_requisitions', null);
        $newCount = 0;

        if ($lastViewed) {
            $newCount = RisSlip::whereIn('status', ['draft', 'approved'])
                ->where('created_at', '>', $lastViewed)
                ->count();
        }

        return response()->json([
            'count' => $totalPending,          // Total needing attention
            'draft_count' => $draftCount,      // Pending approval
            'approved_count' => $approvedCount, // Approved but not issued
            'new_count' => $newCount,          // New since last viewed
            'has_new' => $newCount > 0
        ]);
    }

    /**
     * Get user-specific notification counts (FOR REGULAR USERS)
     */
    public function getUserNotifications(Request $request)
    {
        $userId = $request->user()->id;

        // Count approved requests created by this user
        $approvedRequestsCount = RisSlip::where('requested_by', $userId)
            ->where('status', 'approved')
            ->count();

        // Count issued supplies waiting for this user to confirm receipt
        $pendingReceiptCount = RisSlip::where('received_by', $userId)
            ->where('status', 'posted')
            ->whereNull('received_at')
            ->count();

        // Get details if needed (optional)
        $approvedRequests = [];
        $pendingReceipts = [];

        if ($approvedRequestsCount > 0) {
            $approvedRequests = RisSlip::where('requested_by', $userId)
                ->where('status', 'approved')
                ->select('ris_id', 'ris_no', 'approved_at')
                ->orderBy('approved_at', 'desc')
                ->limit(5)
                ->get();
        }

        if ($pendingReceiptCount > 0) {
            $pendingReceipts = RisSlip::where('received_by', $userId)
                ->where('status', 'posted')
                ->whereNull('received_at')
                ->select('ris_id', 'ris_no', 'issued_at')
                ->orderBy('issued_at', 'desc')
                ->limit(5)
                ->get();
        }

        return response()->json([
            'approved_requests_count' => $approvedRequestsCount,
            'pending_receipt_count' => $pendingReceiptCount,
            'approved_requests' => $approvedRequests,
            'pending_receipts' => $pendingReceipts,
            'total_notifications' => $approvedRequestsCount + $pendingReceiptCount
        ]);
    }

    /**
     * Mark all pending requisitions as viewed (ADMIN)
     * [Keep existing markAsViewed method as is]
     */
    public function markAsViewed(Request $request)
    {
        // Check if user is admin
        if ($request->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Store the current timestamp in the session
        Session::put('last_viewed_requisitions', now());

        // Return current counts
        $draftCount = RisSlip::where('status', 'draft')->count();
        $approvedCount = RisSlip::where('status', 'approved')->count();

        return response()->json([
            'success' => true,
            'message' => 'Viewing history updated',
            'draft_count' => $draftCount,
            'approved_count' => $approvedCount,
            'count' => $draftCount + $approvedCount
        ]);
    }
}
