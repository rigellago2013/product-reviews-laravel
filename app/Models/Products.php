<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price'];

    public function reviews()
    {
        return $this->hasMany(Reviews::class, 'product_id', 'id');
    }
}
