<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ] ;
    protected $table = 'students_classes';

    public function courses(){
        return $this->hasMany(Course::class,'class_id');
    }
}
