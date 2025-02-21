<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'file_path',
    ];

    // Define the inverse relationship to Property
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
