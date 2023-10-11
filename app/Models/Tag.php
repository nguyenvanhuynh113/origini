<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Tag extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = ['name', 'slug', 'description', 'created_at', 'active'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tags', 'id_product', 'id_tag');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'slug', 'active', 'description'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
