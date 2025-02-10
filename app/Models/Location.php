<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['location_name'];

    // Ensure location names are alphanumeric
    public static function rules()
    {
        return [
            'location_name' => 'required|string|regex:/^[a-zA-Z0-9\s]+$/|unique:locations,location_name|max:255',
        ];
    }
}

