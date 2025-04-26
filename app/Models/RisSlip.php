<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Department;
use App\Models\User;
use App\Models\RisItem;

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
        'purpose',
        'status',
        'approved_by',
        'approved_at',
        'issued_by',
        'issued_at',
        'received_by',
        'received_at',
    ];

    protected $casts = [
        'ris_date'      => 'date',
        'approved_at'   => 'datetime',
        'issued_at'     => 'datetime',
        'received_at'   => 'datetime',
    ];

    /**
     * Original division relationship (returns the FK value if you call $ris->division)
     */
    public function division()
    {
        return $this->belongsTo(Department::class, 'division');
    }

    /**
     * Alias to fetch the related Department model without
     * colliding with the 'division' attribute.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'division');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function items()
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
}
