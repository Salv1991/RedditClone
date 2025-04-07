<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Comment extends Model
{
    protected $fillable = [
        'text',
        'user_id',
        'post_id',
        'likes'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function votedByUsers() {
        return $this->belongsToMany(User::class, 'comment_likes')
            ->withPivot('type')
            ->withTimeStamps();
    }

    public function votesCount() {
        return $this->votedByUsers()->count();
    }
}
