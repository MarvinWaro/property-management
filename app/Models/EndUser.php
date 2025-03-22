<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EndUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'department',
        'designation', // <--- ADD THIS
        'active',
        'excluded',
        'picture',
    ];

    public function properties()
    {
        return $this->hasMany(Property::class, 'end_user_id');
    }
}
