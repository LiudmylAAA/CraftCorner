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
}
