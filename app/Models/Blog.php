<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'content', 'slug', 'image', 'created_at','view'];

    public function keys(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Key::class, 'blog_keys', 'id_blog', 'id_key');
    }

    public function types()
    {
        return $this->belongsToMany(Type::class, 'blog_types', 'id_blog', 'id_type');
    }
}
