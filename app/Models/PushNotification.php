<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts= [
        'target' => 'json'
    ];

    public function getDataAttribute()
    {
        return [
            "title"=> $this->title,
            "description"=> $this->description,
            "image"=> $this->image,
            "type"=> "order_status"
        ];
    }

    public function getImageUrlAttribute($value)
    {
        if ($this->image == null)
            return null;
        else
            return url('/') . asset('storage/' . $this->image);
    }
}
