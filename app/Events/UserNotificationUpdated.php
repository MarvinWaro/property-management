<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // <-- Change here!
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\RisSlip;
use App\Models\User;

class UserNotificationUpdated implements ShouldBroadcastNow // <-- And here!
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

        $this->notification = [
            'type' => $type,
            'data' => $data
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
