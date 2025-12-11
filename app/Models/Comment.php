<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Comment extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'comments';

    protected $fillable = [
        'post_id',
        'user_id',
        'text',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', '_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }
}
