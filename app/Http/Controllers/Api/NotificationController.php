<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RisSlip;
use Illuminate\Support\Facades\Session;

class NotificationController extends Controller
{
    /**
     * Get the count of pending requisitions by status
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
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
     * Mark all pending requisitions as viewed
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
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
