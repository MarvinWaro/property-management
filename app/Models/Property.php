<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_number',  // Added new field here
        'item_name',
        'item_description',
        'serial_no',
        'model_no',
        'acquisition_date',
        'acquisition_cost',
        'unit_of_measure',
        'quantity_per_physical_count',
        'fund',
        'location_id',
        'end_user_id',
        'condition',
        'remarks',
        'active',
        'excluded'
    ];

    /**
     * Relationship: Property belongs to a Location
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Relationship: Property belongs to an End User
     */
    public function endUser()
    {
        return $this->belongsTo(EndUser::class);
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

}
