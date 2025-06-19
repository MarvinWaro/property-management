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

class RequisitionStatusUpdated implements ShouldBroadcastNow // <-- And here!
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $risSlip;
    public $action;
    public $counts;

    public function __construct(RisSlip $risSlip, string $action)
    {
        $this->risSlip = $risSlip;
        $this->action = $action;

        // Get updated counts based on role
        $this->counts = [
            'draft_count' => RisSlip::where('status', 'draft')->count(),
            'approved_count' => RisSlip::where('status', 'approved')->count(),
            'total_count' => RisSlip::where('status', 'draft')->count() +
                            RisSlip::where('status', 'approved')->count()
        ];
    }

    public function broadcastOn()
    {
        $channels = [];

        // Route notifications based on action and workflow
        switch ($this->action) {
            case 'created':
                // New requisition → Notify CAO only
                $channels[] = new PrivateChannel('cao-notifications');
                break;

            case 'approved':
                // CAO approved → Notify Admin for issuance
                $channels[] = new PrivateChannel('admin-notifications');
                // Also notify the requester
                if ($this->risSlip->requested_by) {
                    $channels[] = new PrivateChannel('user.' . $this->risSlip->requested_by);
                }
                break;

            case 'declined':
                // Declined → Notify requester only
                if ($this->risSlip->requested_by) {
                    $channels[] = new PrivateChannel('user.' . $this->risSlip->requested_by);
                }
                break;

            case 'issued':
                // Admin issued → Notify assigned receiver
                if ($this->risSlip->received_by) {
                    $channels[] = new PrivateChannel('user.' . $this->risSlip->received_by);
                }
                break;

            case 'completed':
                // Supplies received → Optionally notify admin/cao for tracking
                $channels[] = new PrivateChannel('admin-notifications');
                $channels[] = new PrivateChannel('cao-notifications');
                break;
        }

        return $channels;
    }

    public function broadcastWith()
    {
        $message = '';

        switch ($this->action) {
            case 'created':
                $message = "New requisition request from {$this->risSlip->requester->name}";
                break;
            case 'approved':
                $message = "Requisition {$this->risSlip->ris_no} approved and ready for issuance";
                break;
            case 'declined':
                $message = "Requisition {$this->risSlip->ris_no} was declined";
                break;
            case 'issued':
                $message = "Supplies for {$this->risSlip->ris_no} are ready for pickup";
                break;
            case 'completed':
                $message = "Supplies for {$this->risSlip->ris_no} have been received";
                break;
        }

        return [
            'ris_id' => $this->risSlip->ris_id,
            'ris_no' => $this->risSlip->ris_no,
            'action' => $this->action,
            'status' => $this->risSlip->status,
            'counts' => $this->counts,
            'requester_name' => $this->risSlip->requester->name ?? null,
            'department_name' => $this->risSlip->department->name ?? null,
            'message' => $message,
            'timestamp' => now()->toIso8601String()
        ];
    }
}
