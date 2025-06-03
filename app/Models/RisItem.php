<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Department;

class RisItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'item_id';

    protected $fillable = [
        'ris_id',
        'supply_id',
        'quantity_requested',
        'stock_available',
        'quantity_issued',
        'remarks',
    ];

    protected $casts = [
        'quantity_requested' => 'integer',
        'stock_available' => 'boolean',
        'quantity_issued' => 'integer',
    ];

    public function risSlip(): BelongsTo
    {
        return $this->belongsTo(RisSlip::class, 'ris_id', 'ris_id');
    }

    public function supply(): BelongsTo
    {
        return $this->belongsTo(Supply::class, 'supply_id', 'supply_id');
    }
    
}
