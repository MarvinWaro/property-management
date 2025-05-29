<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyTransaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_id';

    protected $fillable = [
        'supply_id',
        'transaction_type',
        'transaction_date',
        'reference_no',
        'quantity',
        'unit_cost',
        'total_cost',
        'department_id',
        'user_id',
        'fund_cluster',
        'remarks',
        'requested_by',
        'received_by',
        'balance_quantity', // Added this field
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'unit_cost'        => 'decimal:2',
        'total_cost'       => 'decimal:2',
    ];

    public function supply()     { return $this->belongsTo(Supply::class, 'supply_id'); }
    public function department() { return $this->belongsTo(Department::class, 'department_id'); }
    public function user()       { return $this->belongsTo(User::class, 'user_id'); }


    public function users()
    {
        return $this->belongsToMany(User::class, 'user_transactions', 'transaction_id', 'user_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function requesters()
    {
        return $this->users()->wherePivot('role', 'requester');
    }

    public function receivers()
    {
        return $this->users()->wherePivot('role', 'receiver');
    }


}
