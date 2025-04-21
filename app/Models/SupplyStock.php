<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplyStock extends Model
{
    use HasFactory;

    protected $primaryKey = 'stock_id';

    protected $fillable = [
        'supply_id',
        'quantity_on_hand',
        'unit_cost',
        'total_cost',
        'expiry_date',
        'status',
        'fund_cluster',
        'days_to_consume',
        'remarks',
    ];

    protected $casts = [
        'quantity_on_hand' => 'integer',
        'unit_cost'        => 'decimal:2',
        'total_cost'       => 'decimal:2',
        'expiry_date'      => 'date',
        'days_to_consume'  => 'integer',
    ];

    public function supply(): BelongsTo
    {
        return $this->belongsTo(Supply::class, 'supply_id', 'supply_id');
    }
}
