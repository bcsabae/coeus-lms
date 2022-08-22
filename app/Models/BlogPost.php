<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function blogPostCategory()
    {
        return $this->hasMany(BlogPostCategory::class);
    }

    public static function boot()
    {
        parent::boot();

        //delete relations
        static::deleting(function (BlogPost $post) {
            $post->blogPostCategory()->delete();
        });
    }
}
