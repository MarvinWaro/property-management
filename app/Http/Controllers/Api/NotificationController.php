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

        // Get the last viewed timestamp from session
        $lastViewed = Session::get('last_viewed_requisitions', null);

        // Base query for pending requisitions
        $query = RisSlip::where('status', 'draft');

        // If we have a last viewed timestamp, only count requisitions after that time
        if ($lastViewed) {
            $query->where('created_at', '>', $lastViewed);
        }

        // Get the count
        $pendingCount = $query->count();

        return response()->json([
            'count' => $pendingCount
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

        return response()->json([
            'success' => true,
            'message' => 'All requisitions marked as viewed'
        ]);
    }
}
