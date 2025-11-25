<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	use HasFactory;
	public function replies()
{
    return $this->hasMany(Comment::class, 'parent_id')->with('user');
}
protected $fillable = [
        'user_id',
        'lesson_id',
        'content',
        'parent_id',
];


public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
}

public function user()
{
return $this->belongsTo(User::class, 'user_id');
}
}
