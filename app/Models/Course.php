<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'img',
        'class_id',
        'demo',
        'order'
    ];

    public function subjects(){
        return $this->hasMany(Subject::class);
    }


    public function studentClass(){
        return $this->belongsTo(StudentClass::class);
    }
}
