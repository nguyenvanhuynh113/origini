<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['code', 'discount_value', 'coupon_star_date', 'coupon_end_date', 'quantity'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_coupons', 'id_product', 'id_coupon');
    }
}
