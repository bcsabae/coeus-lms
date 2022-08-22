<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDependency extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id',
        'dependency_id'
    ];

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function dependency() {
        return $this->belongsTo(Course::class, 'dependency_id');
    }
}
