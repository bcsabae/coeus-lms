<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function courseCategory()
    {
        return $this->hasMany(CourseCategory::class);
    }

    public function course()
    {
        return $this->belongsToMany(Course::class, 'course_categories');
    }

    public function blogPostCategory()
    {
        return $this->hasMany(BlogPostCategory::class);
    }
}
