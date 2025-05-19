<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supply extends Model
{
    protected $primaryKey = 'supply_id';

    protected $fillable = [
        'stock_no',
        'item_name',
        'description',
        'unit_of_measurement',
        'category_id',
        'supplier_id',      // Add this
        'department_id',    // Add this
        'reorder_point',
        'acquisition_cost',
        'is_active'
    ];

    protected $casts = [
        'acquisition_cost' => 'decimal:2',
        'reorder_point' => 'integer',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Add these relationships
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Get the stock records for this supply
     */
    public function stocks()
    {
        return $this->hasMany(SupplyStock::class, 'supply_id', 'supply_id');
    }
}
