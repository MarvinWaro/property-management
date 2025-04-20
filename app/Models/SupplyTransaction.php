<?php
// Model: app/Models/SupplyTransaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'balance_quantity',
        'department_id',
        'user_id',
        'remarks',
    ];

    public function supply()
    {
        return $this->belongsTo(Supply::class, 'supply_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
