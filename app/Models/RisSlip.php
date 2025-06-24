<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Department;
use App\Models\User;
use App\Models\RisItem;
use App\Constants\RisStatus;

class RisSlip extends Model
{
    use HasFactory;

    protected $primaryKey = 'ris_id';

    protected $fillable = [
        'ris_no',
        'ris_date',
        'entity_name',
        'division',
        'office',
        'fund_cluster',
        'responsibility_center_code',
        'requested_by',
        'requester_signature_type',
        'purpose',
        'status',
        'approved_by',
        'approved_at',
        'approver_signature_type',
        'issued_by',
        'issued_at',
        'issuer_signature_type',
        'received_by',
        'received_at',
        'receiver_signature_type',
        'declined_by',
        'declined_at',
        'decline_reason',
    ];

    protected $casts = [
        'ris_date'      => 'date',
        'approved_at'   => 'datetime',
        'issued_at'     => 'datetime',
        'received_at'   => 'datetime',
        'declined_at'   => 'datetime',
    ];

    protected $attributes = [
        'status' => RisStatus::DRAFT,
        'requester_signature_type' => 'sgd',
        'approver_signature_type' => 'sgd',
        'issuer_signature_type' => 'sgd',
        'receiver_signature_type' => 'sgd',
    ];

    // Relationships
    public function division()
    {
        return $this->belongsTo(Department::class, 'division');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'division');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(RisItem::class, 'ris_id', 'ris_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function decliner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'declined_by');
    }

    // Status helper methods
    public function canBeApproved(): bool
    {
        return RisStatus::canBeApproved($this->status);
    }

    public function canBeDeclined(): bool
    {
        return RisStatus::canBeDeclined($this->status);
    }

    public function canBeIssued(): bool
    {
        return RisStatus::canBeIssued($this->status);
    }

    public function isCompleted(): bool
    {
        return RisStatus::isCompleted($this->status) && $this->received_at;
    }

    public function isFinal(): bool
    {
        return RisStatus::isFinal($this->status);
    }

    public function getStatusLabelAttribute(): string
    {
        return RisStatus::getLabel($this->status);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return RisStatus::getBadgeClass($this->status);
    }

    // Validation rules
    public static function getValidationRules(): array
    {
        return [
            'entity_name' => 'required|string|max:255',
            'division' => 'required|exists:departments,id',
            'office' => 'nullable|string|max:255',
            'fund_cluster' => 'nullable|string|max:20',
            'responsibility_center_code' => 'nullable|string|max:50',
            'purpose' => 'required|string|max:1000',
            'status' => 'required|in:' . implode(',', RisStatus::all()),
        ];
    }

    // Scopes
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeDraft($query)
    {
        return $query->byStatus(RisStatus::DRAFT);
    }

    public function scopeApproved($query)
    {
        return $query->byStatus(RisStatus::APPROVED);
    }

    public function scopePosted($query)
    {
        return $query->byStatus(RisStatus::POSTED);
    }

    public function scopeDeclined($query)
    {
        return $query->byStatus(RisStatus::DECLINED);
    }

    public function scopePendingReceipt($query)
    {
        return $query->byStatus(RisStatus::POSTED)->whereNull('received_at');
    }

    public function scopeCompleted($query)
    {
        return $query->byStatus(RisStatus::POSTED)->whereNotNull('received_at');
    }
}
