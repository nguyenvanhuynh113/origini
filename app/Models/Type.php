<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'created_at', 'active'];

    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_types', 'id_type', 'id_blog');
    }
}
