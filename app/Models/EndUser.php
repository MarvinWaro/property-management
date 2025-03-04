<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EndUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone_number', 'department', 'active', 'excluded', 'picture'
    ];

    // In App\Models\EndUser.php

    public function properties()
    {
        return $this->hasMany(Property::class, 'end_user_id');
    }


}



