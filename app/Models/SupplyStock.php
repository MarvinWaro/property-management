<?php
// app/Models/SupplyStock.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class SupplyStock extends Model
{
    use HasFactory;

    // ---------------------------------------------------------------------
    // BASIC CONFIG
    // ---------------------------------------------------------------------
    protected $table      = 'supply_stocks';
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
    // NEW METHOD: Get Current Moving Average from Ledger Logic
    // ---------------------------------------------------------------------
    public function getCurrentMovingAverageAttribute()
    {
        // Get all transactions for this supply and fund cluster
        $transactions = SupplyTransaction::where('supply_id', $this->supply_id)
            ->where('fund_cluster', $this->fund_cluster)
            ->orderBy('transaction_date')
            ->orderBy('created_at')
            ->get();

        $runningBalance = 0;
        $runningTotalCost = 0;
        $currentYear = Carbon::now()->year;

        // Process each transaction using the exact same logic as SupplyLedgerCardController
        foreach ($transactions as $transaction) {
            $unitCost = $transaction->unit_cost ?? 0;

            if ($transaction->transaction_type == 'receipt') {
                $runningBalance += $transaction->quantity;
                $runningTotalCost += $transaction->quantity * $unitCost;
            } elseif ($transaction->transaction_type == 'issue') {
                $runningBalance -= $transaction->quantity;
                $runningTotalCost -= $transaction->quantity * $unitCost;
            } else {
                // For adjustments, recalculate total for adjustments
                $runningBalance = $transaction->balance_quantity ?? $runningBalance;
                $runningTotalCost = $runningBalance * $unitCost;
            }
        }

        // Calculate final moving average
        return $runningBalance > 0 ? $runningTotalCost / $runningBalance : 0;
    }

    // ---------------------------------------------------------------------
    // NEW METHOD: Get Current Total Value from Ledger Logic
    // ---------------------------------------------------------------------
    public function getCurrentTotalValueAttribute()
    {
        return $this->quantity_on_hand * $this->current_moving_average;
    }

    // ---------------------------------------------------------------------
    // FORMATTED ACCESSORS
    // ---------------------------------------------------------------------
    public function getCurrentUnitCostAttribute()
    {
        return number_format($this->current_moving_average, 2);
    }

    public function getCurrentBalanceTotalCostAttribute()
    {
        return number_format($this->current_total_value, 2);
    }

    // ---------------------------------------------------------------------
    // ACCESSORS – CURRENT UNIT COST (moving-average) - Keep for backwards compatibility
    // ---------------------------------------------------------------------
    public function getCurrentUnitCostRawAttribute()
    {
        return $this->current_moving_average;
    }

    /**
     * Raw value for current total cost
     */
    public function getCurrentBalanceTotalCostRawAttribute()
    {
        return $this->current_total_value;
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
