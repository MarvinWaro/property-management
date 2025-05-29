<?php
// app/Models/SupplyStock.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplyStock extends Model
{
    use HasFactory;

    // ---------------------------------------------------------------------
    // BASIC CONFIG
    // ---------------------------------------------------------------------
    protected $table      = 'supply_stocks';   // keep / adjust if you use a custom name
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
        // if you later change the column to DECIMAL(15,6) keep the cast as string
        'unit_cost'        => 'decimal:2',
        'total_cost'       => 'decimal:2',
        'expiry_date'      => 'date',
        'days_to_consume'  => 'integer',
    ];

    // ---------------------------------------------------------------------
    // RELATIONSHIPS
    // ---------------------------------------------------------------------
    public function supply(): BelongsTo
    {
        return $this->belongsTo(Supply::class, 'supply_id', 'supply_id');
    }

    public function latestTransaction()
    {
        return $this->hasOne(SupplyTransaction::class, 'supply_id', 'supply_id')
                    ->where('fund_cluster', $this->fund_cluster)
                    ->latest('transaction_date')
                    ->latest('created_at');
    }

    // ---------------------------------------------------------------------
    // ACCESSORS – CURRENT UNIT COST (moving-average)
    // ---------------------------------------------------------------------
    public function getCurrentUnitCostRawAttribute()
    {
        $tx = SupplyTransaction::where('supply_id', $this->supply_id)
              ->where('fund_cluster', $this->fund_cluster)
              ->latest('transaction_date')
              ->latest('created_at')
              ->first();

        return $tx && $tx->balance_unit_cost
               ? $tx->balance_unit_cost
               : $this->unit_cost;
    }

    public function getCurrentUnitCostAttribute()
    {
        return number_format($this->current_unit_cost_raw, 2);
    }

    // ---------------------------------------------------------------------
    // ACCESSORS – CURRENT *TOTAL* COST OF WHAT REMAINS
    // ---------------------------------------------------------------------
    /**
     * Raw value taken directly from summary row – avoids
     * re-multiplying rounded unit costs.
     */
    public function getCurrentBalanceTotalCostRawAttribute()
    {
        return $this->total_cost;          // <- no calculation
    }

    /**
     * Formatted string (₱1,234.56) for display.
     */
    public function getCurrentBalanceTotalCostAttribute()
    {
        return number_format($this->current_balance_total_cost_raw, 2);
    }

    // ---------------------------------------------------------------------
    //  DYNAMIC STATUS + STYLING HELPERS (unchanged)
    // ---------------------------------------------------------------------
    public function getDynamicStatusAttribute()
    {
        if (in_array($this->status, ['depleted', 'expired'])) {
            return $this->status;
        }

        if ($this->quantity_on_hand <= 0) {
            return 'depleted';
        }

        if ($this->supply && $this->quantity_on_hand <= $this->supply->reorder_point) {
            return 'low_stock';
        }

        return 'available';
    }

    public function getStatusBackgroundAttribute()
    {
        return match ($this->dynamic_status) {
            'depleted', 'expired' => 'bg-red-50 dark:bg-red-900/20',
            'low_stock'           => 'bg-yellow-50 dark:bg-yellow-900/20',
            default               => 'bg-white dark:bg-gray-800',
        };
    }

    public function getStatusBadgeColorAttribute()
    {
        return match ($this->dynamic_status) {
            'depleted', 'expired' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            'low_stock'           => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'reserved'            => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            default               => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        };
    }

    public function getStatusDisplayAttribute()
    {
        return match ($this->dynamic_status) {
            'low_stock' => 'Low Stock',
            'depleted'  => 'Depleted',
            'expired'   => 'Expired',
            'reserved'  => 'Reserved',
            default     => 'Available',
        };
    }

    // ---------------------------------------------------------------------
    // STATIC HELPERS (unchanged)
    // ---------------------------------------------------------------------
    public static function getAvailableStockForSupply($supplyId)
    {
        return self::where('supply_id', $supplyId)
                   ->where('status', 'available')
                   ->where('quantity_on_hand', '>', 0)
                   ->get();
    }

    public static function getTotalAvailableQuantity($supplyId)
    {
        return self::where('supply_id', $supplyId)
                   ->where('status', 'available')
                   ->sum('quantity_on_hand');
    }

    public static function getAvailableQuantityByFundCluster($supplyId, $fundCluster)
    {
        return self::where('supply_id', $supplyId)
                   ->where('status', 'available')
                   ->where('fund_cluster', $fundCluster)
                   ->sum('quantity_on_hand');
    }
}
