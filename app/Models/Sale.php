<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getImgUrlAttribute($value): string|\Illuminate\Contracts\Routing\UrlGenerator|null
    {
        if ($this->img != null)
            return url('/storage/' . $this->img);
        else
            return url('assets/logo.png');
    }
}
