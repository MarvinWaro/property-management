<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\RisSlip;

class RequisitionStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $risSlip;
    public $action;
    public $counts;

    public function __construct(RisSlip $risSlip, string $action)
    {
        $this->risSlip = $risSlip;
        $this->action = $action;

        // Get updated counts for admin/cao
        $this->counts = [
            'draft_count' => RisSlip::where('status', 'draft')->count(),
            'approved_count' => RisSlip::where('status', 'approved')->count(),
            'total_count' => RisSlip::where('status', 'draft')->count() +
                            RisSlip::where('status', 'approved')->count()
        ];
    }

    public function broadcastOn()
    {
        $channels = [
            new PrivateChannel('admin-notifications'),
        ];

        // Also broadcast to the specific user if approved or issued
        if ($this->action === 'approved' && $this->risSlip->requested_by) {
            $channels[] = new PrivateChannel('user.' . $this->risSlip->requested_by);
        }

        if ($this->action === 'issued' && $this->risSlip->received_by) {
            $channels[] = new PrivateChannel('user.' . $this->risSlip->received_by);
        }

        return $channels;
    }

    public function broadcastWith()
    {
        return [
            'ris_id' => $this->risSlip->ris_id,
            'ris_no' => $this->risSlip->ris_no,
            'action' => $this->action,
            'status' => $this->risSlip->status,
            'counts' => $this->counts,
            'requester_name' => $this->risSlip->requester->name ?? null,
            'department_name' => $this->risSlip->department->name ?? null,
        ];
    }
}
