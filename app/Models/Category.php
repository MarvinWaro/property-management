<?php

namespace App\Models;
use App\Models\Supply;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function supplies()
    {
        return $this->hasMany(Supply::class, 'category_id');
    }
}
