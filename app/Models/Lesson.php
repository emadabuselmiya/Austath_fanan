<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "video",
        "subject_id",
        "img",
        "order"
    ] ;

    public function subject(){
        return $this->belongsTo(Subject::class);
    }
}
