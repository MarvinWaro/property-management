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

    /**
     * Get the dynamic status based on quantity and reorder point
     */
    public function getDynamicStatusAttribute()
    {
        // If manually set to depleted or expired, respect that
        if (in_array($this->status, ['depleted', 'expired'])) {
            return $this->status;
        }

        // If quantity is 0, it's depleted
        if ($this->quantity_on_hand <= 0) {
            return 'depleted';
        }

        // Check against reorder point
        if ($this->supply && $this->quantity_on_hand <= $this->supply->reorder_point) {
            return 'low_stock';
        }

        // Otherwise, it's available
        return 'available';
    }

    /**
     * Get the background color class based on dynamic status
     */
    public function getStatusBackgroundAttribute()
    {
        switch ($this->dynamic_status) {
            case 'depleted':
                return 'bg-red-50 dark:bg-red-900/20';
            case 'low_stock':
                return 'bg-yellow-50 dark:bg-yellow-900/20';
            case 'expired':
                return 'bg-red-50 dark:bg-red-900/20';
            default:
                return 'bg-white dark:bg-gray-800';
        }
    }

    /**
     * Get the status badge color based on dynamic status
     */
    public function getStatusBadgeColorAttribute()
    {
        switch ($this->dynamic_status) {
            case 'depleted':
                return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
            case 'low_stock':
                return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
            case 'expired':
                return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
            case 'reserved':
                return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
            default:
                return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
        }
    }

    /**
     * Get the display text for status
     */
    public function getStatusDisplayAttribute()
    {
        switch ($this->dynamic_status) {
            case 'low_stock':
                return 'Low Stock';
            case 'depleted':
                return 'Depleted';
            case 'expired':
                return 'Expired';
            case 'reserved':
                return 'Reserved';
            default:
                return 'Available';
        }
    }

    /**
     * Get all available stocks for a specific supply.
     *
     * @param int $supplyId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAvailableStockForSupply($supplyId)
    {
        return self::where('supply_id', $supplyId)
                   ->where('status', 'available')
                   ->where('quantity_on_hand', '>', 0)
                   ->get();
    }

    /**
     * Get total available quantity across all fund clusters.
     *
     * @param int $supplyId
     * @return int
     */
    public static function getTotalAvailableQuantity($supplyId)
    {
        return self::where('supply_id', $supplyId)
                   ->where('status', 'available')
                   ->sum('quantity_on_hand');
    }

    /**
     * Get available quantity for a specific fund cluster.
     *
     * @param int $supplyId
     * @param string $fundCluster
     * @return int
     */
    public static function getAvailableQuantityByFundCluster($supplyId, $fundCluster)
    {
        return self::where('supply_id', $supplyId)
                   ->where('status', 'available')
                   ->where('fund_cluster', $fundCluster)
                   ->sum('quantity_on_hand');
    }
}
