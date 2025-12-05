<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'target' => 'json'
    ];

    public function getDataAttribute(): array
    {
        return [
            "title" => $this->title,
            "description" => $this->description,
            "image" => $this->image,
            "url" => $this->url
        ];
    }

    public function getImageUrlAttribute($value): string|\Illuminate\Contracts\Routing\UrlGenerator|\Illuminate\Contracts\Foundation\Application|null
    {
        if ($this->image != null)
            return url('storage/' . $this->image);

        return null;
    }
}
