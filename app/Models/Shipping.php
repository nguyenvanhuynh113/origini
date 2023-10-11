<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipping extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'active', 'created_at', 'fee', 'estimated_date'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_shipings', 'id_product', 'id_shipping');
    }
}
