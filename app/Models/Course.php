<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    //use this naming convention so laravel translates it to access_right_id
    public function accessRight()
    {
        return $this->belongsTo('App\Models\AccessRight');
    }

    public function courseDependency()
    {
        return $this->hasMany(CourseDependency::class);
    }

    public function dependency()
    {
        return $this->belongsToMany(Course::class, 'course_dependencies', 'course_id', 'dependency_id');
    }

    public function content()
    {
        return $this->hasMany(Content::class);
    }

    public function courseCategory()
    {
        return $this->hasMany(CourseCategory::class);
    }

    public function category()
    {
        return $this->belongsToMany(Category::class, 'course_categories');
    }

    public function courseTake()
    {
        return $this->hasMany(CourseTake::class);
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'course_takes');
    }
}
