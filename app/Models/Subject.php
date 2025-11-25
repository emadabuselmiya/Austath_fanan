<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'course_id',
        'order'
    ] ;

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function lessons(){
        return $this->hasMany(Lesson::class);
    }
}
