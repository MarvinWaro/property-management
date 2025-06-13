<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RisSlip;
use Illuminate\Support\Facades\Session;

class NotificationController extends Controller
{
    /**
     * Get the count of pending requisitions
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

        // Always return the total count of draft requisitions
        $pendingCount = RisSlip::where('status', 'draft')->count();

        // Optional: Still track new ones for other features
        $lastViewed = Session::get('last_viewed_requisitions', null);
        $newCount = 0;

        if ($lastViewed) {
            $newCount = RisSlip::where('status', 'draft')
                ->where('created_at', '>', $lastViewed)
                ->count();
        }

        return response()->json([
            'count' => $pendingCount,      // Always show total for badge
            'new_count' => $newCount,      // Optional: for highlighting new items
            'has_new' => $newCount > 0
        ]);
    }

    /**
     * Mark all pending requisitions as viewed
     * This now only tracks viewing history without affecting badge count
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

        // Still return the current total count
        $pendingCount = RisSlip::where('status', 'draft')->count();

        return response()->json([
            'success' => true,
            'message' => 'Viewing history updated',
            'count' => $pendingCount
        ]);
    }
}
