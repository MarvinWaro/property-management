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
use Hashids\Hashids;

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
        // NEW: Manual entry fields
        'is_manual_entry',
        'reference_source',
        'manual_entry_by',
        'manual_entry_at',
        'manual_entry_notes',
    ];

    // app/Models/RisSlip.php

    protected $casts = [
        'ris_date'        => 'date',
        'approved_at'     => 'datetime',
        'issued_at'       => 'datetime',
        'received_at'     => 'datetime',
        'declined_at'     => 'datetime',
        'manual_entry_at' => 'datetime',
        'is_manual_entry' => 'boolean',

        // 🔧 add these so ids are always integers
        'division'        => 'integer',
        'requested_by'    => 'integer',
        'approved_by'     => 'integer',
        'issued_by'       => 'integer',
        'received_by'     => 'integer',
        'declined_by'     => 'integer',
        'manual_entry_by' => 'integer',
    ];


    protected $attributes = [
        'status' => RisStatus::DRAFT,
        'requester_signature_type' => 'sgd',
        'approver_signature_type' => 'sgd',
        'issuer_signature_type' => 'sgd',
        'receiver_signature_type' => 'sgd',
        'is_manual_entry' => false,
    ];

    // ─── Hashids: obfuscate ris_id in URLs ───
    protected static function getHashids(): Hashids
    {
        return new Hashids('ris_slip_salt_key', 10);
    }

    public static function encodeId(int $id): string
    {
        return static::getHashids()->encode($id);
    }

    public static function decodeHash(string $hash): ?int
    {
        $decoded = static::getHashids()->decode($hash);
        return $decoded[0] ?? null;
    }

    public function getRouteKey(): string
    {
        return static::encodeId($this->getKey());
    }

    public function resolveRouteBinding($value, $field = null): ?self
    {
        if ($field) {
            return $this->where($field, $value)->first();
        }

        // Try to decode the hash first
        $id = static::decodeHash($value);

        if ($id !== null) {
            return $this->where($this->getKeyName(), $id)->first();
        }

        // Fallback: try as raw ID (for backwards compatibility)
        return $this->where($this->getKeyName(), $value)->first();
    }

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

    // NEW: Manual entry relationship
    public function manualEntryUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manual_entry_by');
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

    // NEW: Manual entry helper methods
    public function isManualEntry(): bool
    {
        return $this->is_manual_entry;
    }

    public function isHistoricalEntry(): bool
    {
        return $this->is_manual_entry && $this->ris_date->lt(now()->subWeek());
    }

    public function getEntryTypeAttribute(): string
    {
        if ($this->is_manual_entry) {
            return $this->isHistoricalEntry() ? 'Historical Entry' : 'Manual Entry';
        }
        return 'User Request';
    }

    public function getStatusLabelAttribute(): string
    {
        return RisStatus::getLabel($this->status);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return RisStatus::getBadgeClass($this->status);
    }

    // Enhanced validation rules for manual entries
    public static function getValidationRules(bool $isManualEntry = false): array
    {
        $rules = [
            'entity_name' => 'required|string|max:255',
            'division' => 'required|exists:departments,id',
            'office' => 'nullable|string|max:255',
            'fund_cluster' => 'nullable|string|max:20',
            'responsibility_center_code' => 'nullable|string|max:50',
            'purpose' => 'required|string|max:1000',
            'status' => 'required|in:' . implode(',', RisStatus::all()),
        ];

        if ($isManualEntry) {
            $rules['ris_date'] = [
                'required',
                'date',
                'before_or_equal:today',
                'after_or_equal:' . now()->subYears(5)->format('Y-m-d')
            ];
            $rules['requested_by'] = 'required|exists:users,id';
            $rules['reference_source'] = 'nullable|string|max:255';
            $rules['final_status'] = 'required|in:completed,posted,declined';
        }

        return $rules;
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

    // NEW: Scopes for manual entries
    public function scopeManualEntries($query)
    {
        return $query->where('is_manual_entry', true);
    }

    public function scopeUserRequests($query)
    {
        return $query->where('is_manual_entry', false);
    }

    public function scopeHistoricalEntries($query)
    {
        return $query->where('is_manual_entry', true)
                    ->where('ris_date', '<', now()->subWeek());
    }

    // NEW: Boot method to set manual entry metadata
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->is_manual_entry) {
                $model->manual_entry_by = auth()->id();
                $model->manual_entry_at = now();
            }
        });
    }
}
