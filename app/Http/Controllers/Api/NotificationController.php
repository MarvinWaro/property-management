<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RisSlip;

class NotificationController extends Controller
{
    /**
     * Get initial counts for admin/cao users
     * This is only called on page load, not for polling
     */
    public function getInitialCounts(Request $request)
    {
        // Check if user is admin or cao
        if (!in_array($request->user()->role, ['admin', 'cao'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get counts for different statuses
        $draftCount = RisSlip::where('status', 'draft')->count();
        $approvedCount = RisSlip::where('status', 'approved')->count();
        $totalPending = $draftCount + $approvedCount;

        return response()->json([
            'count' => $totalPending,
            'draft_count' => $draftCount,
            'approved_count' => $approvedCount
        ]);
    }

    /**
     * Get initial user notification counts
     * This is only called on page load for regular users
     */
    public function getUserInitialCounts(Request $request)
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

        return response()->json([
            'approved_requests_count' => $approvedRequestsCount,
            'pending_receipt_count' => $pendingReceiptCount,
            'total_notifications' => $approvedRequestsCount + $pendingReceiptCount
        ]);
    }
}
