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

    public function getImgUrlAttribute($value): string|\Illuminate\Contracts\Routing\UrlGenerator|null
    {
        if ($this->img != null)
            return url('/storage/' . $this->img);
        else
            return url('assets/logo.png');
    }

    public function getVideoUrlAttribute($value): string|\Illuminate\Contracts\Routing\UrlGenerator|null
    {
        if ($this->video != null)
            return url('/storage/' . $this->video);
        else
            return null;
    }

    public function subject(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
