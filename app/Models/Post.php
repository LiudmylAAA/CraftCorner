<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Post;
class Post extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'posts';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'image_url',
        'likes',
        'created_at',
        
    ];

    protected $casts = [
        'likes' => 'array',
    ];
     public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', '_id');
    }
}
