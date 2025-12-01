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

    public function getImgUrlAttribute($value): string|\Illuminate\Contracts\Routing\UrlGenerator|null
    {
        if ($this->img != null)
            return url('/storage/' . $this->img);
        else
            return url('assets/logo.png');
    }

    public function getDemoUrlAttribute($value): string|\Illuminate\Contracts\Routing\UrlGenerator|null
    {
        if ($this->demo != null)
            return url('/storage/' . $this->demo);
        else
            return null;
    }

    public function subjects(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subject::class);
    }


    public function studentClass(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StudentClass::class);
    }
}
