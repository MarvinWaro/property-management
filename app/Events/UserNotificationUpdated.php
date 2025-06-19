<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\RisSlip;
use App\Models\User;

class UserNotificationUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $counts;
    public $notification;

    public function __construct(int $userId, string $type, $data = null)
    {
        $this->userId = $userId;

        // Get updated counts for this user
        $approvedRequestsCount = RisSlip::where('requested_by', $userId)
            ->where('status', 'approved')
            ->count();

        $pendingReceiptCount = RisSlip::where('received_by', $userId)
            ->where('status', 'posted')
            ->whereNull('received_at')
            ->count();

        $this->counts = [
            'approved_requests_count' => $approvedRequestsCount,
            'pending_receipt_count' => $pendingReceiptCount,
            'total_notifications' => $approvedRequestsCount + $pendingReceiptCount
        ];

        // Get user profile photos for notifications
        $caoUser = User::where('role', 'cao')->first();
        $adminUser = User::where('role', 'admin')->first();
        $currentUser = User::find($userId);

        // Enhance notification data with profile photos
        $enhancedData = is_array($data) ? $data : [];
        $enhancedData['cao_photo'] = $caoUser?->profile_photo_url;
        $enhancedData['admin_photo'] = $adminUser?->profile_photo_url;
        $enhancedData['receiver_name'] = $currentUser?->name;
        $enhancedData['receiver_photo'] = $currentUser?->profile_photo_url;

        $this->notification = [
            'type' => $type,
            'data' => $enhancedData
        ];
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->userId);
    }

    public function broadcastWith()
    {
        return [
            'counts' => $this->counts,
            'notification' => $this->notification,
            'timestamp' => now()->toIso8601String()
        ];
    }
}
